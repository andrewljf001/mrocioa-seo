const {Card,Badge,IconButton,Select} = window.MrocioaDesignSystem_b37fe9;
window.LangCtx=window.LangCtx||React.createContext('bi');
window.tt=window.tt||(l=>(zh,en)=>l==='zh'?zh:l==='en'?en:zh+' '+en);
const AT_DESC={'AT+GMR':'查询固件版本 query firmware version','AT+RST':'软复位 soft reset','AT+CWLAP':'扫描接入点 list access points','AT':'测试 AT 就绪 test AT startup'};
const crc16=bytes=>{let c=0xFFFF;for(const b of bytes){c^=b;for(let i=0;i<8;i++)c=c&1?(c>>1)^0xA001:c>>1;}return c;};
function detect(raw){
  const s=raw.replace(/\\r|\\n/g,'').trim();
  if(/^([0-9A-Fa-f]{2}\s+){7,}/.test(s)&&/^00\s+FF\s+FF\s+FF\s+FF\s+FF\s+FF\s+00/i.test(s))return 'edid';
  if(/^\$[A-Z]{2,5},/.test(s))return 'nmea';
  if(/\{.*\}/.test(s))return 'json';
  if(/^AT(\+|$)/i.test(s)||/^(OK|ERROR|READY|ATE?\d?)$/.test(s)||/^\+\w+:/.test(s))return 'at';
  if(/^([0-9A-Fa-f]{2}\s+){3,}[0-9A-Fa-f]{2}$/.test(s))return 'modbus';
  return 'text';
}
function decodeLine(raw,proto){
  const s=raw.replace(/\\r|\\n/g,'').trim();
  const rows=[];const R=(f,v,m,err)=>rows.push({f,v,m,err});
  if(proto==='at'){
    if(AT_DESC[s.toUpperCase()])R('命令 CMD',s,AT_DESC[s.toUpperCase()]);
    else if(s==='OK')R('响应 RESP','OK','命令执行成功 command succeeded');
    else if(s==='ERROR')R('响应 RESP','ERROR','命令执行失败 command failed',true);
    else if(s==='READY')R('事件 EVT','READY','模块启动完成 module ready');
    else if(/^\+(\w+):(.*)/.test(s)){const m=s.match(/^\+(\w+):(.*)/);R('URC','+'+m[1],'主动上报 unsolicited result code');R('参数 PARAMS',m[2],m[1]==='CWLAP'?'(加密,SSID,RSSI)':'');}
    else if(/^AT\+(\w+)=(.*)/i.test(s)){const m=s.match(/^AT\+(\w+)=(.*)/i);R('命令 CMD','AT+'+m[1],'设置命令 set command');R('参数 PARAMS',m[2],'');}
    else if(/^AT\+(\w+)\?/i.test(s))R('命令 CMD',s,'查询命令 query command');
    else R('文本 TEXT',s,'未识别 AT 帧 unrecognized');
  }else if(proto==='nmea'){
    const star=s.indexOf('*');
    const body=star>0?s.slice(1,star):s.slice(1);
    const cs=star>0?s.slice(star+1):null;
    const fields=body.split(',');
    const type=fields[0];
    R('语句 SENTENCE','$'+type,{GNGGA:'定位数据 fix data',GNRMC:'推荐最简数据 recommended minimum',GPGSV:'可见卫星 satellites in view',GNGLL:'地理定位 geographic position'}[type]||'NMEA 0183');
    if(/GGA$/.test(type)&&fields.length>9){
      R('UTC',fields[1],'时间 hhmmss.ss');
      R('纬度 LAT',fields[2]+' '+fields[3],'ddmm.mmmm');
      R('经度 LON',fields[4]+' '+fields[5],'dddmm.mmmm');
      R('质量 FIX',fields[6],fields[6]==='1'?'GPS 单点定位':fields[6]==='0'?'无定位 no fix':'差分/RTK');
      R('卫星数 SATS',fields[7],'');R('HDOP',fields[8],'');R('海拔 ALT',fields[9]+' m','');
    }else fields.slice(1).forEach((f,i)=>f&&R('字段 F'+(i+1),f,''));
    if(cs){
      let x=0;for(const c of body)x^=c.charCodeAt(0);
      const ok=x.toString(16).toUpperCase().padStart(2,'0')===cs.toUpperCase();
      R('校验 CHECKSUM','*'+cs,ok?'校验通过 OK':'校验错误 expect *'+x.toString(16).toUpperCase().padStart(2,'0'),!ok);
    }
  }else if(proto==='json'){
    const m=s.match(/\{.*\}/);
    const pre=s.slice(0,s.indexOf('{')).trim();
    if(pre)R('前缀 PREFIX',pre,/^\+IPD/.test(pre)?'网络数据到达 network data received':'');
    try{
      const obj=JSON.parse(m[0]);
      Object.entries(obj).forEach(([k,v])=>R('字段 '+k.toUpperCase(),String(v),typeof v==='number'?'数值 number':'字符串 string'));
      R('校验 SYNTAX','JSON','格式合法 valid');
    }catch(e){R('校验 SYNTAX',m[0],'JSON 解析失败 parse error: '+e.message,true);}
  }else if(proto==='modbus'){
    const bytes=s.split(/\s+/).map(h=>parseInt(h,16));
    if(bytes.length<4||bytes.some(isNaN)){R('错误 ERR',s,'需要空格分隔的十六进制字节 need hex bytes',true);}
    else{
      R('从站 SLAVE','0x'+bytes[0].toString(16).toUpperCase().padStart(2,'0'),'地址 '+bytes[0]);
      const fc=bytes[1];
      const FC={1:'读线圈 read coils',2:'读离散输入 read discrete inputs',3:'读保持寄存器 read holding regs',4:'读输入寄存器 read input regs',5:'写单线圈 write single coil',6:'写单寄存器 write single reg',15:'写多线圈 write multiple coils',16:'写多寄存器 write multiple regs'};
      if(fc&0x80)R('功能码 FC','0x'+fc.toString(16).toUpperCase(),'异常响应 exception · 码 '+bytes[2],true);
      else R('功能码 FC','0x'+fc.toString(16).toUpperCase().padStart(2,'0'),FC[fc]||'自定义 custom');
      if(!(fc&0x80)&&bytes.length>=8&&(fc<=6)){
        R('起始地址 ADDR','0x'+((bytes[2]<<8|bytes[3]).toString(16).toUpperCase().padStart(4,'0')),String(bytes[2]<<8|bytes[3]));
        R('数量/值 QTY','0x'+((bytes[4]<<8|bytes[5]).toString(16).toUpperCase().padStart(4,'0')),String(bytes[4]<<8|bytes[5]));
      }
      const got=bytes[bytes.length-2]|(bytes[bytes.length-1]<<8);
      const want=crc16(bytes.slice(0,-2));
      const ok=got===want;
      R('CRC16','0x'+got.toString(16).toUpperCase().padStart(4,'0'),ok?'校验通过 OK':'校验错误 expect 0x'+want.toString(16).toUpperCase().padStart(4,'0'),!ok);
    }
  }else if(proto==='edid'){
    const bytes=s.split(/\s+/).map(h=>parseInt(h,16)).filter(n=>!isNaN(n));
    R('分片 FRAGMENTS',String(bytes.length)+' B',bytes.length<128?'不完整 — EDID 块为 128 B，Ctrl+点击合并更多行 incomplete: merge more lines':'完整块 complete block',bytes.length<128);
    const hdrOk=bytes.length>=8&&bytes[0]===0&&bytes.slice(1,7).every(b=>b===0xFF)&&bytes[7]===0;
    R('头部 HEADER',bytes.slice(0,8).map(b=>b.toString(16).toUpperCase().padStart(2,'0')).join(' '),hdrOk?'校验通过 OK':'头部错误 bad header',!hdrOk);
    if(bytes.length>=10){const v=bytes[8]<<8|bytes[9];const ch=n=>String.fromCharCode(64+((v>>n)&31));R('厂商 VENDOR',ch(10)+ch(5)+ch(0),'PNP ID');}
    if(bytes.length>=12)R('产品码 PRODUCT','0x'+((bytes[11]<<8|bytes[10]).toString(16).toUpperCase().padStart(4,'0')),'little-endian');
    if(bytes.length>=16)R('序列号 SERIAL','0x'+((bytes[15]<<24|bytes[14]<<16|bytes[13]<<8|bytes[12])>>>0).toString(16).toUpperCase(),'');
    if(bytes.length>=18)R('生产日期 DATE',`第 ${bytes[16]} 周 / ${1990+bytes[17]}`,'week/year');
    if(bytes.length>=20)R('版本 VERSION',`EDID ${bytes[18]}.${bytes[19]}`,'');
    if(bytes.length>=21)R('输入 INPUT',bytes[20]&0x80?'数字 Digital':'模拟 Analog',bytes[20]&0x80?['未定义','DVI','HDMI-a','HDMI-b','MDDI','DisplayPort'][(bytes[20]>>0)&0xF]||'':'');
    if(bytes.length>=23)R('尺寸 SIZE',`${bytes[21]} × ${bytes[22]} cm`,'');
    if(bytes.length>=128){const sum=bytes.slice(0,128).reduce((a,b)=>a+b,0)&0xFF;R('校验和 CHECKSUM','0x'+bytes[127].toString(16).toUpperCase().padStart(2,'0'),sum===0?'校验通过 OK (sum%256=0)':'校验错误 sum%256='+sum,sum!==0);}
  }else{
    R('文本 TEXT',s||'(空 empty)','未匹配已知协议 no protocol matched');
    R('HEX',[...s].map(c=>c.charCodeAt(0).toString(16).toUpperCase().padStart(2,'0')).join(' '),s.length+' B');
  }
  return rows;
}
const protoList=[['自动 Auto','auto'],['AT 指令','at'],['NMEA (GPS)','nmea'],['JSON','json'],['Modbus RTU','modbus'],['EDID','edid'],['纯文本 Text','text']];
function ProtocolPanel({sel:selLines,onExpand,onClose}){
  const t=window.tt(React.useContext(window.LangCtx));
  const [sel,setSel]=React.useState('auto');
  const lines=selLines||[];
  const combined=lines.map(l=>l.ascii.replace(/\\r|\\n/g,'').trim()).join(' ');
  const proto=lines.length?(sel==='auto'?detect(combined):sel):null;
  const rows=lines.length?decodeLine(combined,proto):[];
  return <Card title={t('协议解析','DECODE')} style={{height:210,flex:'none'}}
    actions={<>
      {lines.length>0&&onExpand&&<Badge tone="neutral" style={{cursor:'pointer'}} onClick={onExpand} title={t('自动向两侧合并相邻的十六进制分片行','auto-merge adjacent hex fragment lines')}>{t('自动拼帧','AUTO-FRAME')}</Badge>}
      {lines.length>1&&<Badge>{lines.length} {t('行合并','LINES MERGED')}</Badge>}
      {lines.length>0&&<Badge tone="accent">{sel==='auto'?t('自动识别','AUTO')+': '+proto.toUpperCase():proto.toUpperCase()}</Badge>}
      <div style={{width:150}}><Select options={protoList.map(([label,value])=>({value,label}))} value={sel} onChange={e=>setSel(e.target.value)} style={{height:'var(--ctl-h-sm)',fontSize:'var(--fs-label)'}}/></div>
      <IconButton onClick={onClose}>×</IconButton></>}
    bodyStyle={{padding:0,display:'flex',flexDirection:'column',minHeight:0}}>
    {!lines.length&&<div style={{flex:1,display:'flex',alignItems:'center',justifyContent:'center',color:'var(--fg-3)',fontSize:12}}>{t('// 点击监视区任意一行解析；长帧被分成多行时用 Ctrl+点击依次合并','// click any line to decode; Ctrl+click to merge a frame split across lines')}</div>}
    {lines.length>0&&<div style={{flex:1,overflowY:'auto',minHeight:0}}>
      <div style={{padding:'5px 12px',borderBottom:'1px solid var(--border-0)',fontFamily:'var(--font-mono)',fontSize:'var(--fs-terminal)',display:'flex',gap:10,alignItems:'center'}}>
        <span style={{color:'var(--fg-3)'}}>{lines[0].time}{lines.length>1?' → '+lines[lines.length-1].time:''}</span>
        <Badge tone={lines[0].kind==='tx'?'accent':'ok'}>{lines[0].kind}</Badge>
        <span style={{color:'var(--fg-1)',whiteSpace:'nowrap',overflow:'hidden',textOverflow:'ellipsis'}}>{combined}</span>
      </div>
      <table style={{width:'100%',borderCollapse:'collapse',fontSize:'var(--fs-terminal)',fontFamily:'var(--font-mono)'}}>
        <tbody>{rows.map((r,i)=><tr key={i} style={{borderBottom:'1px solid var(--border-0)'}}>
          <td style={{padding:'3px 12px',color:'var(--fg-3)',whiteSpace:'nowrap',width:130}}>{r.f}</td>
          <td style={{padding:'3px 12px',color:r.err?'var(--err-0)':'var(--accent-0)',whiteSpace:'nowrap'}}>{r.v}</td>
          <td style={{padding:'3px 12px',color:r.err?'var(--err-0)':'var(--fg-2)'}}>{r.m}</td>
        </tr>)}</tbody>
      </table>
    </div>}
  </Card>;
}
window.ProtocolPanel=ProtocolPanel;
