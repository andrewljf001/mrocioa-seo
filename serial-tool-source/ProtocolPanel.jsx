const {Card,Badge,IconButton,Select} = window.MrocioaDesignSystem_b37fe9;
window.LangCtx=window.LangCtx||React.createContext('bi');
window.tt=window.tt||(l=>(zh,en)=>l==='zh'?zh:l==='en'?en:zh+' '+en);

const AT_DESC={'AT+GMR':'查询固件版本 query firmware version','AT+RST':'软复位 soft reset','AT+CWLAP':'扫描接入点 list access points','AT':'测试 AT 就绪 test AT startup'};
const MODBUS_FC={1:'读线圈 read coils',2:'读离散输入 read discrete inputs',3:'读保持寄存器 read holding registers',4:'读输入寄存器 read input registers',5:'写单线圈 write single coil',6:'写单寄存器 write single register',15:'写多线圈 write multiple coils',16:'写多寄存器 write multiple registers',22:'屏蔽写寄存器 mask write register',23:'读写多寄存器 read/write multiple registers'};
const CEC_DEVICES=['TV','Recording Device 1','Recording Device 2','Tuner 1','Playback Device 1','Audio System','Tuner 2','Tuner 3','Playback Device 2','Recording Device 3','Tuner 4','Playback Device 3','Reserved 1','Reserved 2','Free Use','Broadcast / Unregistered'];
const CEC_OPCODES={
  0x04:'Image View On',0x0D:'Text View On',0x36:'Standby',0x44:'User Control Pressed',0x45:'User Control Released',
  0x46:'Give OSD Name',0x47:'Set OSD Name',0x70:'System Audio Mode Request',0x71:'Give Audio Status',
  0x72:'Set System Audio Mode',0x7A:'Report Audio Status',0x80:'Routing Change',0x81:'Routing Information',
  0x82:'Active Source',0x83:'Give Physical Address',0x84:'Report Physical Address',0x85:'Request Active Source',
  0x86:'Set Stream Path',0x87:'Device Vendor ID',0x89:'Vendor Command',0x8A:'Vendor Remote Button Down',
  0x8B:'Vendor Remote Button Up',0x8C:'Give Device Vendor ID',0x8D:'Menu Request',0x8E:'Menu Status',
  0x8F:'Give Device Power Status',0x90:'Report Power Status',0x9D:'Inactive Source',0x9E:'CEC Version',
  0x9F:'Get CEC Version',0xA0:'Vendor Command With ID',0xC0:'Initiate ARC',0xC1:'Report ARC Initiated',
  0xC2:'Report ARC Terminated',0xC3:'Request ARC Initiation',0xC4:'Request ARC Termination',0xC5:'Terminate ARC'
};
const CEC_KEYS={0x00:'Select',0x01:'Up',0x02:'Down',0x03:'Left',0x04:'Right',0x09:'Root Menu',0x0A:'Setup Menu',0x0B:'Contents Menu',0x0D:'Exit',0x20:'Number 0',0x21:'Number 1',0x22:'Number 2',0x23:'Number 3',0x24:'Number 4',0x25:'Number 5',0x26:'Number 6',0x27:'Number 7',0x28:'Number 8',0x29:'Number 9',0x40:'Power',0x41:'Volume Up',0x42:'Volume Down',0x43:'Mute',0x44:'Play',0x45:'Stop',0x46:'Pause',0x47:'Record',0x48:'Rewind',0x49:'Fast Forward',0x4B:'Forward',0x4C:'Backward'};
const MAV_MESSAGES={0:'HEARTBEAT',1:'SYS_STATUS',2:'SYSTEM_TIME',24:'GPS_RAW_INT',30:'ATTITUDE',33:'GLOBAL_POSITION_INT',65:'RC_CHANNELS',74:'VFR_HUD',76:'COMMAND_LONG',77:'COMMAND_ACK',111:'TIMESYNC',147:'BATTERY_STATUS',253:'STATUSTEXT'};
const MAV_CRC_EXTRA={0:50,1:124,2:137,24:24,30:39,33:104,65:118,74:20,76:152,77:143,111:34,147:154,253:83};
const UBX_CLASSES={0x01:'NAV',0x02:'RXM',0x04:'INF',0x05:'ACK',0x06:'CFG',0x09:'UPD',0x0A:'MON',0x0B:'AID',0x0D:'TIM',0x10:'ESF',0x13:'MGA',0x21:'LOG',0x27:'SEC',0x28:'HNR',0x29:'NAV2'};
const UBX_MESSAGES={'01:07':'NAV-PVT','01:35':'NAV-SAT','05:00':'ACK-NAK','05:01':'ACK-ACK','06:00':'CFG-PRT','06:08':'CFG-RATE','0A:04':'MON-VER','0D:01':'TIM-TP','27:03':'SEC-UNIQID'};
const IR_PROTOCOLS='NEC|NEC2|ONKYO|APPLE|DENON|SHARP|PANASONIC|KASEIKYO|JVC|LG|RC5|RC6|SAMSUNG|SAMSUNGLG|SONY|SIRC|MARANTZ|PRONTO|BOSEWAVE|FAST|WHYNTER';

const cleanRaw=raw=>String(raw||'').replace(/\\r|\\n/g,'').replace(/[\r\n]/g,' ').trim();
const hex2=n=>'0x'+Number(n).toString(16).toUpperCase().padStart(2,'0');
const hex4=n=>'0x'+Number(n).toString(16).toUpperCase().padStart(4,'0');
const bytesText=bytes=>bytes.map(b=>b.toString(16).toUpperCase().padStart(2,'0')).join(' ');
const stripPrefix=s=>s.replace(/^\s*(?:CEC|HDMI[- ]?CEC|IR|I2C|SPI|UART\d*|RS[- ]?(?:232|485)|1[- ]?WIRE|ONEWIRE|UBX|MAVLINK|MAV|CAN|LIN|DMX512?|RDM|DLT?[-/ ]?645)(?:\s+(?:RX|TX|READ|WRITE|FRAME|DATA|CODE|RAW|RAW[- ]?DATA|RAW[- ]?TIMINGS))?\s*[:=>-]?\s*/i,'').trim();
function parseHexBytes(raw){
  const s=stripPrefix(cleanRaw(raw));
  if(!s||!/^(?:(?:0x)?[0-9A-Fa-f]{2})(?:[\s,;:\-]+(?:0x)?[0-9A-Fa-f]{2})*$/.test(s))return null;
  return (s.match(/(?:0x)?[0-9A-Fa-f]{2}/g)||[]).map(v=>parseInt(v.replace(/^0x/i,''),16));
}
const crc16=bytes=>{let c=0xFFFF;for(const b of bytes){c^=b;for(let i=0;i<8;i++)c=c&1?(c>>1)^0xA001:c>>1;}return c;};
const lrc8=bytes=>(-bytes.reduce((sum,b)=>(sum+b)&0xFF,0))&0xFF;
const sum8=bytes=>bytes.reduce((sum,b)=>(sum+b)&0xFF,0);
const within=(value,target,tolerance)=>Math.abs(value-target)<=tolerance;
function mavCrc(bytes,extra){let crc=0xFFFF;const add=b=>{let tmp=b^(crc&0xFF);tmp^=(tmp<<4)&0xFF;crc=((crc>>8)^(tmp<<8)^(tmp<<3)^(tmp>>4))&0xFFFF;};bytes.forEach(add);if(extra!=null)add(extra);return crc;}
function ubxChecksum(bytes){let a=0,b=0;bytes.forEach(v=>{a=(a+v)&0xFF;b=(b+a)&0xFF;});return [a,b];}
function linProtectedId(id){id&=0x3F;const b=n=>(id>>n)&1;const p0=b(0)^b(1)^b(2)^b(4);const p1=1^(b(1)^b(3)^b(4)^b(5));return id|(p0<<6)|(p1<<7);}
function linChecksum(bytes){let sum=0;for(const b of bytes){sum+=b;if(sum>255)sum-=255;}return (~sum)&0xFF;}
function parseNumber(value){if(value==null)return null;const text=String(value).trim();return /^0x/i.test(text)?parseInt(text,16):parseInt(text,10);}
function extractNumber(s,names){const m=s.match(new RegExp('(?:^|\\b)(?:'+names+')\\s*[:=]\\s*(0x[0-9A-F]+|[0-9]+)','i'));return m?parseNumber(m[1]):null;}
function extractByteField(s,names){const m=s.match(new RegExp('(?:^|\\b)(?:'+names+')\\s*[:=]\\s*((?:(?:0x)?[0-9A-F]{2})(?![0-9A-Z])(?:[\\s,;:\\-]+(?:0x)?[0-9A-F]{2}(?![0-9A-Z]))*)','i'));return m?(m[1].match(/(?:0x)?[0-9A-F]{2}/gi)||[]).map(v=>parseInt(v.replace(/^0x/i,''),16)):[];}
function extractCompactBytes(s,names){const m=s.match(new RegExp('(?:^|\\b)(?:'+names+')\\s*[:=]\\s*(0x)?([0-9A-F]{2,})','i'));if(!m||m[2].length%2)return [];return (m[2].match(/../g)||[]).map(v=>parseInt(v,16));}
function directionFromLog(s){if(/(?:^|\b)(?:RX|READ|RECV|RECEIVED)(?:\b|\s*[:=])/i.test(s))return 'RX / READ';if(/(?:^|\b)(?:TX|WRITE|SEND|SENT)(?:\b|\s*[:=])/i.test(s))return 'TX / WRITE';return null;}
function frequencyFromLog(s){const m=s.match(/(?:^|\b)(?:FREQ(?:UENCY)?|SPEED|CLOCK|SCK)\s*[:=]\s*([0-9.]+)\s*(MHZ|KHZ|HZ)?/i);return m?m[1]+' '+(m[2]||'Hz'):null;}
function loggedField(s,names){const m=s.match(new RegExp('(?:^|\\b)(?:'+names+')\\s*[:=]\\s*["\\\']?([^"\\\'\\r\\n]+)','i'));return m?m[1].trim():null;}
function crc8Dallas(bytes){let crc=0;for(const value of bytes){let b=value;for(let i=0;i<8;i++){const mix=(crc^b)&1;crc>>=1;if(mix)crc^=0x8C;b>>=1;}}return crc;}
function modbusRows(bytes,R,checksumLabel,got,want){
  if(bytes.length<3){R('错误 ERR',bytesText(bytes),'帧长度不足 frame too short',true);return;}
  R('从站 SLAVE',hex2(bytes[0]),'地址 '+bytes[0]);
  const fc=bytes[1];
  if(fc&0x80)R('功能码 FC',hex2(fc),'异常响应 exception · code '+(bytes[2]??'?'),true);
  else R('功能码 FC',hex2(fc),MODBUS_FC[fc]||'自定义 custom');
  if(!(fc&0x80)&&bytes.length>=6&&fc<=6){
    R('起始地址 ADDR',hex4((bytes[2]<<8)|bytes[3]),String((bytes[2]<<8)|bytes[3]));
    R('数量/值 QTY',hex4((bytes[4]<<8)|bytes[5]),String((bytes[4]<<8)|bytes[5]));
  }else if(!(fc&0x80)&&bytes.length>3){
    R('数据 DATA',bytesText(bytes.slice(2,-1)),Math.max(0,bytes.length-3)+' B');
  }
  R(checksumLabel,hex2(got),got===want?'校验通过 OK':'校验错误 expect '+hex2(want),got!==want);
}
function detect(raw){
  const s=cleanRaw(raw);const u=s.toUpperCase();const bytes=parseHexBytes(s);
  if(bytes&&bytes.length>=8&&bytes.slice(0,8).join(',')==='0,255,255,255,255,255,255,0')return 'edid';
  if(/^\$[A-Z]{2,5},/.test(s))return 'nmea';
  if(/\{[\s\S]*\}/.test(s))return 'json';
  if(/^AT(?:\+|$)/i.test(s)||/^(OK|ERROR|READY|ATE?\d?)$/i.test(s)||/^\+\w+:/.test(s))return 'at';
  if(/(?:^|\b)IR(?:\s+(?:REMOTE))?(?:\s+(?:CODE|PROTOCOL|ADDRESS|COMMAND|RAW(?:[- ]?(?:DATA|TIMINGS))?))?\s*[:=]/i.test(s)||new RegExp('^(?:'+IR_PROTOCOLS+')\\b','i').test(s))return 'ir';
  if(/(?:^|\b)(?:HDMI[- ]?)?CEC(?:\s+(?:RX|TX|FRAME|OPCODE|OP|SOURCE|SRC|DESTINATION|DEST|DST))?\s*[:=]/i.test(s))return 'cec';
  if(/(?:^|\b)I2C(?:\s+(?:RX|TX|READ|WRITE))?(?:\s|:|=)/i.test(s))return 'i2c';
  if(/(?:^|\b)SPI(?:\s+(?:RX|TX|READ|WRITE))?(?:\s|:|=)/i.test(s))return 'spi';
  if(/(?:^|\b)(?:UART\d*|RS[- ]?(?:232|485))(?:\s+(?:RX|TX))?(?:\s|:|=)/i.test(s))return 'uart';
  if(/(?:^|\b)(?:1[- ]?WIRE|ONEWIRE)(?:\s+(?:ROM|DATA|READ|WRITE))?(?:\s|:|=)/i.test(s))return 'onewire';
  if(/^:[0-9A-F]{8,}(?:\\R|\s)*$/i.test(s))return 'modbus-ascii';
  if(bytes&&bytes[0]===0xB5&&bytes[1]===0x62)return 'ubx';
  if(bytes&&(bytes[0]===0xFD||bytes[0]===0xFE))return 'mavlink';
  if(/(?:^|\b)CAN(?:\s+(?:RX|TX|READ|WRITE|FRAME))?(?:\s|:|=)/i.test(s)||/^(?:T|R)[0-9A-F]{8}[0-8]/i.test(s)||/^(?:t|r)[0-9A-F]{3}[0-8]/.test(s)||/[0-9A-F]{3,8}#[0-9A-F]*/i.test(s)||/^[0-9A-F]{3,8}\s+(?:[0-9A-F]{2}\s*){1,8}$/i.test(s))return 'can';
  if(/(?:^|\b)LIN(?:\s+(?:RX|TX|READ|WRITE|FRAME))?(?:\s|:|=)/i.test(s)||(bytes&&bytes[0]===0x55&&bytes.length>=4))return 'lin';
  if(/(?:^|\b)RDM(?:\s+(?:RX|TX|READ|WRITE|FRAME))?(?:\s|:|=)/i.test(s)||(bytes&&bytes[0]===0xCC&&bytes[1]===0x01))return 'rdm';
  if(/(?:^|\b)DMX(?:512)?(?:\s+(?:RX|TX|READ|WRITE|FRAME))?(?:\s|:|=)/i.test(s))return 'dmx';
  if(/^\/?[A-Z]{3}\d?/.test(s)&&(/^[\/!]/.test(s)||/\d+-\d+:\d+\.\d+/.test(s)))return 'iec62056';
  if(bytes&&bytes.length>=12&&bytes[0]===0x68&&bytes[7]===0x68&&bytes[bytes.length-1]===0x16)return 'dlt645';
  if(/^(?:N\d+\s+)?[GMT]\d+(?:\.\d+)?(?:\s|$)/i.test(s))return 'gcode';
  if(bytes&&bytes.length>=4){const got=bytes[bytes.length-2]|(bytes[bytes.length-1]<<8);if(crc16(bytes.slice(0,-2))===got)return 'modbus';}
  return 'text';
}
function decodeLine(raw,proto){
  const s=cleanRaw(raw);const rows=[];const R=(f,v,m,err)=>rows.push({f,v,m,err});
  if(proto==='at'){
    if(AT_DESC[s.toUpperCase()])R('命令 CMD',s,AT_DESC[s.toUpperCase()]);
    else if(/^OK$/i.test(s))R('响应 RESP','OK','命令执行成功 command succeeded');
    else if(/^ERROR$/i.test(s))R('响应 RESP','ERROR','命令执行失败 command failed',true);
    else if(/^READY$/i.test(s))R('事件 EVT','READY','模块启动完成 module ready');
    else if(/^\+(\w+):(.*)/.test(s)){const m=s.match(/^\+(\w+):(.*)/);R('URC','+'+m[1],'主动上报 unsolicited result code');R('参数 PARAMS',m[2],m[1]==='CWLAP'?'(encryption, SSID, RSSI)':'');}
    else if(/^AT\+(\w+)=(.*)/i.test(s)){const m=s.match(/^AT\+(\w+)=(.*)/i);R('命令 CMD','AT+'+m[1],'设置命令 set command');R('参数 PARAMS',m[2],'');}
    else if(/^AT\+(\w+)\?/i.test(s))R('命令 CMD',s,'查询命令 query command');
    else R('文本 TEXT',s,'未识别 AT 帧 unrecognized');
  }else if(proto==='nmea'){
    const star=s.indexOf('*');const body=star>0?s.slice(1,star):s.slice(1);const cs=star>0?s.slice(star+1,star+3):null;const fields=body.split(',');const type=fields[0];
    const desc={GNGGA:'定位数据 fix data',GPGGA:'定位数据 fix data',GNRMC:'推荐最简数据 recommended minimum',GPRMC:'推荐最简数据 recommended minimum',GPGSV:'可见卫星 satellites in view',GNGLL:'地理定位 geographic position',GNVTG:'航向与地速 course and speed'};
    R('语句 SENTENCE','$'+type,desc[type]||'NMEA 0183');
    if(/GGA$/.test(type)&&fields.length>9){R('UTC',fields[1],'hhmmss.ss');R('纬度 LAT',fields[2]+' '+fields[3],'ddmm.mmmm');R('经度 LON',fields[4]+' '+fields[5],'dddmm.mmmm');R('质量 FIX',fields[6],fields[6]==='1'?'GPS single fix':fields[6]==='0'?'no fix':'differential / RTK');R('卫星数 SATS',fields[7],'');R('HDOP',fields[8],'');R('海拔 ALT',fields[9]+' m','');}
    else fields.slice(1).forEach((value,index)=>value&&R('字段 F'+(index+1),value,''));
    if(cs){let x=0;for(const c of body)x^=c.charCodeAt(0);const want=x.toString(16).toUpperCase().padStart(2,'0');R('校验 CHECKSUM','*'+cs,want===cs.toUpperCase()?'校验通过 OK':'校验错误 expect *'+want,want!==cs.toUpperCase());}
  }else if(proto==='json'){
    const match=s.match(/\{[\s\S]*\}/);const pre=match?s.slice(0,s.indexOf(match[0])).trim():'';if(pre)R('前缀 PREFIX',pre,/^\+IPD/.test(pre)?'network data received':'');
    try{const obj=JSON.parse(match?match[0]:s);Object.entries(obj).forEach(([key,value])=>R('字段 '+key.toUpperCase(),typeof value==='object'?JSON.stringify(value):String(value),typeof value));R('校验 SYNTAX','JSON','格式合法 valid');}catch(error){R('校验 SYNTAX',s,'JSON 解析失败 parse error: '+error.message,true);}
  }else if(proto==='modbus'){
    const bytes=parseHexBytes(s)||[];
    if(bytes.length<4){R('错误 ERR',s,'需要空格分隔的十六进制字节 need hex bytes',true);}
    else{const got=bytes[bytes.length-2]|(bytes[bytes.length-1]<<8);const want=crc16(bytes.slice(0,-2));modbusRows(bytes.slice(0,-1),R,'CRC16',got,want);}
  }else if(proto==='modbus-ascii'){
    const payload=s.replace(/^\s*:/,'').replace(/(?:\\r|\\n|\s)+/g,'');
    if(!/^[0-9A-F]+$/i.test(payload)||payload.length%2){R('错误 ERR',s,'Modbus ASCII 必须为冒号开头的偶数个十六进制字符',true);}
    else{const bytes=(payload.match(/../g)||[]).map(v=>parseInt(v,16));const got=bytes[bytes.length-1];const want=lrc8(bytes.slice(0,-1));modbusRows(bytes,R,'LRC',got,want);}
  }else if(proto==='cec'){
    const direction=directionFromLog(s);if(direction)R('方向 DIRECTION',direction,'from device serial debug log');
    let bytes=parseHexBytes(s)||[];
    if(!bytes.length){let header=extractNumber(s,'header');const src=extractNumber(s,'source|src|initiator'),dst=extractNumber(s,'destination|dest|dst|follower'),opcode=extractNumber(s,'opcode|op');const operands=extractByteField(s,'data|operands?|params?');if(header==null&&src!=null&&dst!=null)header=((src&15)<<4)|(dst&15);if(header!=null){bytes=[header&255];if(opcode!=null)bytes.push(opcode&255,...operands);}}
    if(!bytes.length){R('日志类型 LOG TYPE','HDMI-CEC debug','已识别 CEC 调试日志，但未找到可解析帧 recognized; no decodable frame fields');R('原始日志 RAW',s,'例如 CEC RX = 40 44 41');}
    else{
      const header=bytes[0],src=header>>4,dst=header&0x0F;R('发起设备 INITIATOR',src+' · '+CEC_DEVICES[src],hex2(header));R('目标设备 DESTINATION',dst+' · '+CEC_DEVICES[dst],dst===15?'broadcast':'direct');
      if(bytes.length===1)R('消息 MESSAGE','Polling','仅地址头，无操作码 header only');
      else{const op=bytes[1];R('操作码 OPCODE',hex2(op),CEC_OPCODES[op]||'Unknown / vendor-specific');const operands=bytes.slice(2);if(operands.length)R('参数 OPERANDS',bytesText(operands),operands.length+' B');
        if([0x80,0x81,0x82,0x84,0x86,0x9D].includes(op)&&operands.length>=2)R('物理地址 PHYSICAL',((operands[0]>>4)&15)+'.'+(operands[0]&15)+'.'+((operands[1]>>4)&15)+'.'+(operands[1]&15),'HDMI topology');
        if(op===0x44&&operands.length)R('按键 UI COMMAND',hex2(operands[0]),CEC_KEYS[operands[0]]||'Reserved / vendor-specific');
        if(op===0x90&&operands.length)R('电源状态 POWER',String(operands[0]),['On','Standby','Transition Standby → On','Transition On → Standby'][operands[0]]||'Unknown');
        if(op===0x84&&operands.length>=3)R('设备类型 DEVICE TYPE',String(operands[2]),['TV','Recording','Reserved','Tuner','Playback','Audio System','Pure CEC Switch','Video Processor'][operands[2]]||'Unknown');
      }
    }
    R('来源 SOURCE','Serial debug output','解析设备经串口打印的 CEC 调试内容 decoded from the device log');
  }else if(proto==='ir'){
    const named=s.match(new RegExp('(?:^|\\b)('+IR_PROTOCOLS+')\\b','i'));const protocol=named?named[1].toUpperCase():null;
    if(protocol)R('协议 PROTOCOL',protocol,'decoded IR frame');
    const direction=directionFromLog(s);if(direction)R('方向 DIRECTION',direction,'from device serial debug log');
    const codeMatch=s.match(/(?:^|\b)(?:IR(?:\s+REMOTE)?\s+)?(?:CODE|VALUE)\s*[:=]\s*(0x[0-9A-F]+|[0-9]+|\?+)/i);
    if(codeMatch){const value=codeMatch[1];if(/^\?+$/.test(value))R('红外码 IR CODE',value,'已识别 IR 调试日志；当前日志未给出实际编码 recognized IR debug log; code not supplied');else{try{const big=BigInt(value),hex='0x'+big.toString(16).toUpperCase(),bits=/^0x/i.test(value)?(value.replace(/^0x/i,'').length*4):Math.max(1,big.toString(2).length);R('红外码 IR CODE',hex,'decimal '+big.toString(10));R('估算位数 BITS',String(bits),'按日志数值宽度 calculated from the logged value');}catch(error){R('红外码 IR CODE',value,'无法转换 invalid numeric value',true);}}}
    for(const [label,key] of [['地址 ADDRESS','address'],['命令 COMMAND','command'],['原始值 RAW','raw(?:-data)?'],['位数 BITS','bits']]){const m=s.match(new RegExp(key+'\\s*[:=]\\s*(0x[0-9A-F]+|[0-9]+)','i'));if(m)R(label,m[1],'from device serial debug log');}
    const pronto=s.match(/(?:PRONTO\s*[:=]?\s*)?(0000(?:\s+[0-9A-F]{4}){3,})/i);if(pronto){const words=pronto[1].trim().split(/\s+/).map(v=>parseInt(v,16));const carrier=words[1]?Math.round(1000000/(words[1]*0.241246)):0;R('PRONTO TYPE',hex4(words[0]),words[0]===0?'learned code':'unsupported type');R('载波 CARRIER',carrier?carrier+' Hz':'unknown','');R('序列长度 LENGTH',(words[2]+words[3])*2+' timings','intro + repeat');}
    const rawPart=s.match(/(?:IR\s+RAW|RAW(?:DATA|TIMINGS)?)\s*[:=]\s*([+\-]?\d+(?:[\s,]+[+\-]?\d+){5,})/i);
    if(rawPart){const timings=(rawPart[1].match(/[+\-]?\d+/g)||[]).map(v=>Math.abs(parseInt(v,10)));R('原始脉冲 RAW',timings.length+' timings',bytesText(timings.slice(0,0))||'microseconds');
      let start=0;if(timings.length>2&&timings[0]>15000)start=1;const nec=within(timings[start],9000,1800)&&within(timings[start+1],4500,1000);
      if(nec){const bits=[];for(let i=start+2;i+1<timings.length&&bits.length<32;i+=2){if(!within(timings[i],560,300))break;bits.push(timings[i+1]>1100?1:0);}if(bits.length>=32){const bytes=[];for(let n=0;n<4;n++){let value=0;for(let bit=0;bit<8;bit++)value|=bits[n*8+bit]<<bit;bytes.push(value);}const addrParity=((bytes[0]^bytes[1])===0xFF),cmdParity=((bytes[2]^bytes[3])===0xFF);R('自动识别 DETECTED',addrParity?'NEC':'Extended NEC / Onkyo',bytesText(bytes));R('地址 ADDRESS',addrParity?hex2(bytes[0]):hex4(bytes[0]|(bytes[1]<<8)),addrParity?'inverse byte valid':'16-bit address');R('命令 COMMAND',cmdParity?hex2(bytes[2]):hex4(bytes[2]|(bytes[3]<<8)),cmdParity?'inverse byte valid':'16-bit command');R('校验 PARITY',cmdParity?'OK':'command inverse mismatch',cmdParity?'':'check capture format',!cmdParity);}else R('自动识别 DETECTED','NEC-like','not enough timings for 32 bits',true);}
    }
    if(!protocol&&!rawPart&&!pronto&&!codeMatch)R('日志类型 LOG TYPE','IR debug','已识别红外调试日志，但未找到 code / protocol / raw timings');
    R('支持范围 SUPPORT','NEC · RC5 · RC6 · Sony · Samsung · JVC · Panasonic · LG · Pronto','named decoder output; raw timing auto-decode currently includes NEC family');
    R('来源 SOURCE','Serial debug output','解析设备经串口打印的 IR 调试内容 decoded from the device log');
  }else if(proto==='i2c'){
    const direction=directionFromLog(s);const address=extractNumber(s,'address|addr|slave'),register=extractNumber(s,'register|reg|offset');const frequency=frequencyFromLog(s);const tx=extractByteField(s,'tx|write(?:\\s+data)?|mosi'),rx=extractByteField(s,'rx|read(?:\\s+data)?|miso'),data=extractByteField(s,'data|payload');
    R('日志类型 LOG TYPE','I²C debug','由设备串口日志识别 recognized from serial output');if(direction)R('方向 DIRECTION',direction,'');
    if(address!=null){const note=address<=0x7F?'7-bit address':address<=0x3FF?'10-bit / logged address':'out of I²C address range';R('从机地址 ADDRESS','0x'+address.toString(16).toUpperCase(),note,address>0x3FF);if(address>=0x50&&address<=0x57)R('设备提示 DEVICE HINT','EEPROM range','common 24xx memory address; verify against hardware');}
    if(register!=null)R('寄存器 REGISTER','0x'+register.toString(16).toUpperCase(),String(register));if(tx.length)R('发送数据 TX',bytesText(tx),tx.length+' B');if(rx.length)R('接收数据 RX',bytesText(rx),rx.length+' B');if(!tx.length&&!rx.length&&data.length)R('数据 DATA',bytesText(data),data.length+' B');
    const ack=s.match(/(?:^|\b)(NACK|ACK)(?:\b|\s*[:=])/i);if(ack)R('应答 ACK',ack[1].toUpperCase(),ack[1].toUpperCase()==='ACK'?'acknowledged':'not acknowledged',ack[1].toUpperCase()==='NACK');if(frequency)R('时钟 CLOCK',frequency,'logged bus clock');
    if(address==null&&register==null&&!tx.length&&!rx.length&&!data.length)R('原始日志 RAW',s,'已识别 I²C 调试上下文；未找到 addr / reg / data');
  }else if(proto==='spi'){
    const direction=directionFromLog(s);const mode=extractNumber(s,'mode'),cs=extractNumber(s,'cs|chipselect|chip[- ]?select');const frequency=frequencyFromLog(s);const tx=extractByteField(s,'tx|mosi|write(?:\\s+data)?|cmd'),rx=extractByteField(s,'rx|miso|read(?:\\s+data)?'),data=extractByteField(s,'data|payload');
    R('日志类型 LOG TYPE','SPI debug','由设备串口日志识别 recognized from serial output');if(direction)R('方向 DIRECTION',direction,'');if(mode!=null){const modes=[['0','0'],['0','1'],['1','0'],['1','1']];R('模式 MODE',String(mode),mode>=0&&mode<=3?'CPOL '+modes[mode][0]+' · CPHA '+modes[mode][1]:'expected 0–3',mode<0||mode>3);}if(cs!=null)R('片选 CS',String(cs),'logged chip-select');if(frequency)R('时钟 CLOCK',frequency,'logged SCK');
    if(tx.length){R('MOSI / TX',bytesText(tx),tx.length+' B');const command={0x02:'Page Program',0x03:'Read Data',0x05:'Read Status Register',0x06:'Write Enable',0x0B:'Fast Read',0x20:'Sector Erase',0x9F:'Read JEDEC ID'}[tx[0]];if(command)R('命令 COMMAND',hex2(tx[0]),command);}if(rx.length){R('MISO / RX',bytesText(rx),rx.length+' B');if(tx[0]===0x9F&&rx.length>=3)R('JEDEC ID',bytesText(rx.slice(0,3)),'manufacturer · memory type · capacity');}if(!tx.length&&!rx.length&&data.length)R('数据 DATA',bytesText(data),data.length+' B');if(!tx.length&&!rx.length&&!data.length)R('原始日志 RAW',s,'已识别 SPI 调试上下文；未找到 TX / RX / data');
  }else if(proto==='uart'){
    const transport=(s.match(/\b(UART\d*|RS[- ]?(?:232|485))\b/i)||[])[1]||'UART';const direction=directionFromLog(s);const baud=extractNumber(s,'baud|baudrate|speed');const framing=s.match(/\b([5-9])([NEOMS])([12])\b/i);const data=extractByteField(s,'rx|tx|data|hex|payload');const textValue=(s.match(/(?:^|\b)(?:text|ascii)\s*[:=]\s*["']?([^"'\r\n]+)["']?/i)||[])[1];
    R('日志类型 LOG TYPE',transport.toUpperCase()+' debug','由当前串口日志识别的通讯调试内容');if(direction)R('方向 DIRECTION',direction,'');if(baud!=null)R('波特率 BAUD',String(baud),'bit/s');if(framing)R('帧格式 FRAMING',framing[1]+framing[2].toUpperCase()+framing[3],framing[1]+' data bits · '+framing[2].toUpperCase()+' parity · '+framing[3]+' stop bit(s)');if(data.length){R('数据 DATA',bytesText(data),data.length+' B');const ascii=data.map(b=>b>=32&&b<127?String.fromCharCode(b):'.').join('');R('ASCII',ascii,'printable preview');}if(textValue)R('文本 TEXT',textValue.trim(),'logged text payload');if(!direction&&baud==null&&!framing&&!data.length&&!textValue)R('原始日志 RAW',s,'已识别 UART / RS-232 / RS-485 调试上下文');
  }else if(proto==='onewire'){
    const direction=directionFromLog(s);const familyNames={0x10:'DS18S20 / temperature',0x28:'DS18B20 / temperature',0x22:'DS1822 / temperature',0x2D:'DS2431 / EEPROM',0x01:'DS1990A / serial number'};let rom=extractCompactBytes(s,'rom(?:code)?|id');if(!rom.length)rom=extractByteField(s,'rom(?:code)?|id');const data=extractByteField(s,'data|rx|tx|read|write');
    R('日志类型 LOG TYPE','1-Wire debug','由设备串口日志识别 recognized from serial output');if(direction)R('方向 DIRECTION',direction,'');if(rom.length){R('ROM CODE',bytesText(rom),rom.length+' B');R('系列 FAMILY',hex2(rom[0]),familyNames[rom[0]]||'device-specific');if(rom.length===8){const want=crc8Dallas(rom.slice(0,7)),got=rom[7];R('ROM CRC8',hex2(got),got===want?'Dallas/Maxim CRC OK':'expect '+hex2(want),got!==want);}}if(data.length)R('数据 DATA',bytesText(data),data.length+' B');if(!rom.length&&!data.length)R('原始日志 RAW',s,'已识别 1-Wire 调试上下文；未找到 ROM / data');
  }else if(proto==='ubx'){
    const bytes=parseHexBytes(s)||[];
    if(bytes.length<8||bytes[0]!==0xB5||bytes[1]!==0x62){R('错误 ERR',s,'UBX frame must start B5 62',true);}
    else{const cls=bytes[2],id=bytes[3],len=bytes[4]|(bytes[5]<<8),key=cls.toString(16).toUpperCase().padStart(2,'0')+':'+id.toString(16).toUpperCase().padStart(2,'0');R('同步 SYNC','B5 62','u-blox binary');R('消息 MESSAGE',UBX_MESSAGES[key]||((UBX_CLASSES[cls]||'CLASS '+hex2(cls))+'-'+hex2(id)),key);R('负载长度 LENGTH',len+' B','little-endian');const expected=6+len+2;R('帧长度 FRAME',bytes.length+' B',bytes.length===expected?'complete':'expect '+expected+' B',bytes.length!==expected);if(bytes.length>=expected){const want=ubxChecksum(bytes.slice(2,6+len)),got=bytes.slice(6+len,8+len);R('校验 CHECKSUM',bytesText(got),got[0]===want[0]&&got[1]===want[1]?'校验通过 OK':'expect '+bytesText(want),got[0]!==want[0]||got[1]!==want[1]);}}
  }else if(proto==='mavlink'){
    const bytes=parseHexBytes(s)||[];
    if(bytes.length<8||![0xFD,0xFE].includes(bytes[0])){R('错误 ERR',s,'MAVLink frame must start FD (v2) or FE (v1)',true);}
    else{const v2=bytes[0]===0xFD,len=bytes[1],seq=bytes[v2?4:2],sys=bytes[v2?5:3],comp=bytes[v2?6:4],msg=v2?(bytes[7]|(bytes[8]<<8)|(bytes[9]<<16)):bytes[5],header=v2?10:6,crcPos=header+len,signed=v2&&!!(bytes[2]&1),expected=crcPos+2+(signed?13:0);R('版本 VERSION',v2?'MAVLink 2':'MAVLink 1',hex2(bytes[0]));R('消息 MESSAGE',String(msg),MAV_MESSAGES[msg]||'dialect-specific');R('序列 SEQUENCE',String(seq),'packet-loss tracking');R('系统/组件 SYS/COMP',sys+' / '+comp,'source IDs');R('负载长度 LENGTH',len+' B','');R('帧长度 FRAME',bytes.length+' B',bytes.length===expected?'complete':'expect '+expected+' B',bytes.length!==expected);if(bytes.length>=crcPos+2){const got=bytes[crcPos]|(bytes[crcPos+1]<<8),extra=MAV_CRC_EXTRA[msg],want=mavCrc(bytes.slice(1,crcPos),extra);R('CRC-16',hex4(got),extra==null?'CRC_EXTRA unknown for this dialect':got===want?'校验通过 OK':'expect '+hex4(want),extra!=null&&got!==want);}if(signed)R('签名 SIGNATURE','13 B',bytes.length>=expected?'present':'incomplete',bytes.length<expected);}
  }else if(proto==='can'){
    let id=null,data=[],remote=false,format='serial adapter log';const direction=directionFromLog(s);const keyedId=extractNumber(s,'can[- ]?id|id'),keyedData=extractByteField(s,'data|payload|bytes');const slcan=s.match(/^([tTrR])([0-9A-F]+)([0-8])([0-9A-F]*)$/i);
    if(/(?:^|\b)CAN\b/i.test(s)&&keyedId!=null){id=keyedId;data=keyedData;format='device serial debug log';}
    else if(slcan){const ext=slcan[1]===slcan[1].toUpperCase(),idLen=ext?8:3;id=parseInt(slcan[2].slice(0,idLen),16);const dlc=parseInt(slcan[3],16);remote=/[rR]/.test(slcan[1]);data=(slcan[4].match(/../g)||[]).slice(0,dlc).map(v=>parseInt(v,16));format='SLCAN / LAWICEL '+(ext?'29-bit':'11-bit');}
    else{const normalized=s.replace(/^\([^)]*\)\s*/,'').replace(/^\w+\s+/,'');const hash=normalized.match(/([0-9A-F]{3,8})#([0-9A-F]*)/i);const spaced=normalized.match(/(?:^|\s)([0-9A-F]{3,8})\s+((?:[0-9A-F]{2}\s*){1,64})$/i);const m=hash||spaced;if(m){id=parseInt(m[1],16);data=((m[2]||'').match(/[0-9A-F]{2}/gi)||[]).map(v=>parseInt(v,16));format=hash?'candump':'ELM327 / adapter log';}}
    if(id==null)R('错误 ERR',s,'Use t1238AABB…, 123#AABB, or 7E8 03 41 0D 28',true);
    else{if(direction)R('方向 DIRECTION',direction,'from device serial debug log');R('格式 FORMAT',format,remote?'remote request':'data frame');R('CAN ID','0x'+id.toString(16).toUpperCase(),id>0x7FF?'extended 29-bit':'standard 11-bit');R('数据 DATA',bytesText(data),data.length+' B');if(data.length){const type=data[0]>>4;let payload=data;if(type===0){const len=data[0]&15;R('ISO-TP','Single Frame',len+' payload bytes');payload=data.slice(1,1+len);}else if(type===1){R('ISO-TP','First Frame',(((data[0]&15)<<8)|data[1])+' payload bytes');payload=data.slice(2);}else if(type===2){R('ISO-TP','Consecutive Frame','sequence '+(data[0]&15));payload=data.slice(1);}else if(type===3){R('ISO-TP','Flow Control',['Continue','Wait','Overflow'][data[0]&15]||'status '+(data[0]&15));payload=data.slice(3);}if(payload.length>=2&&(payload[0]&0x40)){const mode=payload[0],pid=payload[1],a=payload[2],b=payload[3];R('OBD 响应 MODE',hex2(mode),'request mode '+hex2(mode&0x3F));R('PID',hex2(pid),{0x05:'Engine coolant temperature',0x0C:'Engine RPM',0x0D:'Vehicle speed',0x11:'Throttle position'}[pid]||'');if(pid===0x05&&a!=null)R('数值 VALUE',(a-40)+' °C','');if(pid===0x0C&&b!=null)R('数值 VALUE',(((a<<8)|b)/4)+' rpm','');if(pid===0x0D&&a!=null)R('数值 VALUE',a+' km/h','');if(pid===0x11&&a!=null)R('数值 VALUE',(a*100/255).toFixed(1)+' %','');}}}
  }else if(proto==='lin'){
    const direction=directionFromLog(s);const bytes=parseHexBytes(s)||[];const sync=bytes.indexOf(0x55);const loggedPid=extractNumber(s,'pid'),loggedId=extractNumber(s,'id'),loggedData=extractByteField(s,'data|payload'),loggedChecksum=extractNumber(s,'checksum|cs');if(direction)R('方向 DIRECTION',direction,'from device serial debug log');
    if(sync<0||bytes.length<sync+4){const id=loggedPid!=null?(loggedPid&0x3F):loggedId,pid=loggedPid!=null?loggedPid:(id!=null?linProtectedId(id):null);if(pid==null&&!loggedData.length)R('日志类型 LOG TYPE','LIN debug','已识别 LIN 调试日志；未找到 PID / ID / data');else{if(pid!=null){R('受保护 ID PID',hex2(pid),'frame ID '+hex2(pid&0x3F));R('PID 奇偶校验 PARITY',linProtectedId(pid&0x3F)===pid?'OK':'expect '+hex2(linProtectedId(pid&0x3F)),'P0/P1',linProtectedId(pid&0x3F)!==pid);}if(loggedData.length)R('数据 DATA',bytesText(loggedData),loggedData.length+' B');if(loggedChecksum!=null&&pid!=null){const classic=linChecksum(loggedData),enhanced=linChecksum([pid,...loggedData]);R('校验 CHECKSUM',hex2(loggedChecksum),loggedChecksum===enhanced?'enhanced checksum OK':loggedChecksum===classic?'classic checksum OK':'expect '+hex2(enhanced)+' enhanced / '+hex2(classic)+' classic',loggedChecksum!==enhanced&&loggedChecksum!==classic);}}}
    else{const pid=bytes[sync+1],id=pid&0x3F,data=bytes.slice(sync+2,-1),got=bytes[bytes.length-1],classic=linChecksum(data),enhanced=linChecksum([pid,...data]);R('同步 SYNC','0x55','break is not represented in the printed log');R('受保护 ID PID',hex2(pid),'frame ID '+hex2(id));R('PID 奇偶校验 PARITY',linProtectedId(id)===pid?'OK':'expect '+hex2(linProtectedId(id)),'P0/P1',linProtectedId(id)!==pid);R('数据 DATA',bytesText(data),data.length+' B');R('校验 CHECKSUM',hex2(got),got===enhanced?'enhanced checksum OK':got===classic?'classic checksum OK':'expect '+hex2(enhanced)+' enhanced / '+hex2(classic)+' classic',got!==enhanced&&got!==classic);}
    R('来源 SOURCE','Serial debug output','解析设备经串口打印的 LIN 调试内容 decoded from the device log');
  }else if(proto==='dmx'||proto==='rdm'){
    const bytes=parseHexBytes(s)||[];const isRdm=proto==='rdm'||(bytes[0]===0xCC&&bytes[1]===0x01);
    if(isRdm){if(bytes.length<26)R('错误 ERR',s,'RDM packet too short; expected start CC 01',true);else{const len=bytes[2],uid=v=>v.map(b=>b.toString(16).toUpperCase().padStart(2,'0')).join(':');const cc=bytes[20],pid=(bytes[21]<<8)|bytes[22],pdl=bytes[23];R('类型 TYPE','RDM','ANSI E1.20 over DMX512');R('消息长度 LENGTH',len+' B',bytes.length>=len?'complete':'incomplete',bytes.length<len);R('目标 UID DEST',uid(bytes.slice(3,9)),'');R('来源 UID SRC',uid(bytes.slice(9,15)),'');R('事务 TRANSACTION',String(bytes[15]),'');R('命令类 COMMAND CLASS',hex2(cc),{0x10:'DISCOVERY_COMMAND',0x11:'DISCOVERY_RESPONSE',0x20:'GET_COMMAND',0x21:'GET_RESPONSE',0x30:'SET_COMMAND',0x31:'SET_RESPONSE'}[cc]||'Unknown');R('参数 ID PID',hex4(pid),{0x0060:'DEVICE_INFO',0x0080:'DEVICE_MODEL_DESCRIPTION',0x00F0:'DMX_START_ADDRESS',0x0200:'SENSOR_DEFINITION',0x0201:'SENSOR_VALUE'}[pid]||'');R('参数数据 PARAMS',bytesText(bytes.slice(24,24+pdl)),pdl+' B');if(bytes.length>=len&&len>=2){const got=(bytes[len-2]<<8)|bytes[len-1],want=bytes.slice(0,len-2).reduce((sum,b)=>(sum+b)&0xFFFF,0);R('校验 CHECKSUM',hex4(got),got===want?'校验通过 OK':'expect '+hex4(want),got!==want);}}}
    else if(!bytes.length)R('错误 ERR',s,'Use DMX: 00 FF 00 80 …',true);else{R('起始码 START CODE',hex2(bytes[0]),bytes[0]===0?'DMX512 level data':'alternate start code');R('通道数 SLOTS',String(Math.max(0,bytes.length-1)),'maximum 512');bytes.slice(1,17).forEach((value,index)=>R('CH '+(index+1),String(value),Math.round(value*100/255)+' %'));if(bytes.length>17)R('其余通道 MORE','+'+(bytes.length-17),'not shown');}
  }else if(proto==='iec62056'){
    if(/^\//.test(s)){const m=s.match(/^\/([A-Z]{3})([^\s]*)/);R('类型 TYPE','Identification response','IEC 62056-21');if(m){R('厂商 MANUFACTURER',m[1],'three-letter ID');R('标识 IDENTIFIER',m[2]||'(none)','meter / baud information');}}
    else{const obis=s.match(/(\d+-\d+:\d+\.\d+(?:\.\d+)?)\*?\d*\(([^)]*)\)/);if(obis){R('OBIS',obis[1],'meter data object');R('数值 VALUE',obis[2],'');}else if(/ACK|\x06/i.test(s))R('控制 CONTROL','ACK','baud-rate / mode negotiation');else R('文本 TEXT',s,'IEC 62056-21 serial meter line');}
  }else if(proto==='dlt645'){
    const bytes=parseHexBytes(s)||[];
    if(bytes.length<12||bytes[0]!==0x68||bytes[7]!==0x68||bytes[bytes.length-1]!==0x16)R('错误 ERR',s,'DL/T 645 frame must be 68 A0…A5 68 C L DATA CS 16',true);
    else{const addr=bytes.slice(1,7).reverse().map(b=>b.toString(16).padStart(2,'0')).join('');const control=bytes[8],len=bytes[9],data=bytes.slice(10,10+len),got=bytes[10+len],want=sum8(bytes.slice(0,10+len));R('表地址 ADDRESS',addr,'BCD, transmitted low byte first');R('控制码 CONTROL',hex2(control),(control&0x80?'response':'request')+' · function '+hex2(control&0x1F));R('数据长度 LENGTH',len+' B','');R('原始数据 RAW',bytesText(data),'encoded with +0x33');R('解码数据 DECODED',bytesText(data.map(b=>(b-0x33)&0xFF)),'subtract 0x33');R('校验 CHECKSUM',hex2(got),got===want?'校验通过 OK':'expect '+hex2(want),got!==want);}
  }else if(proto==='gcode'){
    const noComment=s.replace(/\([^)]*\)|;.*$/g,'').trim();const star=noComment.lastIndexOf('*');const body=star>=0?noComment.slice(0,star):noComment;const got=star>=0?parseInt(noComment.slice(star+1),10):null;const line=body.match(/^N(\d+)\s+/i);const cmd=body.match(/(?:^|\s)([GMT]\d+(?:\.\d+)?)/i);if(line)R('行号 LINE',line[1],'');if(cmd){const c=cmd[1].toUpperCase();R('命令 COMMAND',c,{G0:'Rapid move',G1:'Linear move',G28:'Home axes',G90:'Absolute positioning',G91:'Relative positioning',M104:'Set hotend temperature',M105:'Read temperatures',M109:'Set and wait for hotend',M114:'Report position',M115:'Firmware information',M119:'Endstop status',M503:'Report settings',M999:'Restart after error'}[c]||'G-code command');}else R('错误 ERR',s,'No G/M/T command found',true);for(const m of body.matchAll(/\b([XYZEFSP])(-?\d+(?:\.\d+)?)/gi))R('参数 '+m[1].toUpperCase(),m[2],'');if(got!=null){let want=0;for(const ch of body)want^=ch.charCodeAt(0);R('校验 CHECKSUM',String(got),got===want?'XOR OK':'expect '+want,got!==want);}
  }else if(proto==='edid'){
    const bytes=parseHexBytes(s)||[];R('分片 FRAGMENTS',bytes.length+' B',bytes.length<128?'不完整 — EDID block is 128 B; merge more lines':'完整块 complete block',bytes.length<128);const hdrOk=bytes.length>=8&&bytes[0]===0&&bytes.slice(1,7).every(b=>b===0xFF)&&bytes[7]===0;R('头部 HEADER',bytesText(bytes.slice(0,8)),hdrOk?'校验通过 OK':'头部错误 bad header',!hdrOk);if(bytes.length>=10){const v=(bytes[8]<<8)|bytes[9],ch=n=>String.fromCharCode(64+((v>>n)&31));R('厂商 VENDOR',ch(10)+ch(5)+ch(0),'PNP ID');}if(bytes.length>=12)R('产品码 PRODUCT',hex4((bytes[11]<<8)|bytes[10]),'little-endian');if(bytes.length>=18)R('生产日期 DATE','第 '+bytes[16]+' 周 / '+(1990+bytes[17]),'week/year');if(bytes.length>=20)R('版本 VERSION','EDID '+bytes[18]+'.'+bytes[19],'');if(bytes.length>=128){const sum=bytes.slice(0,128).reduce((a,b)=>a+b,0)&0xFF;R('校验和 CHECKSUM',hex2(bytes[127]),sum===0?'校验通过 OK':'sum%256='+sum,sum!==0);}
  }else{
    R('文本 TEXT',s||'(空 empty)','未匹配已知协议 no protocol matched');R('HEX',[...s].map(c=>c.charCodeAt(0).toString(16).toUpperCase().padStart(2,'0')).join(' '),s.length+' B');
  }
  return rows;
}

const protoList=[
  ['自动 Auto','auto'],['AT 指令','at'],['NMEA 0183 (GPS)','nmea'],['JSON','json'],
  ['Modbus RTU','modbus'],['Modbus ASCII','modbus-ascii'],['HDMI-CEC','cec'],['红外 Infrared','ir'],
  ['I²C 调试日志','i2c'],['SPI 调试日志','spi'],['UART / RS-232 / RS-485','uart'],['1-Wire 调试日志','onewire'],
  ['MAVLink 1 / 2','mavlink'],['u-blox UBX','ubx'],['CAN / OBD-II','can'],['LIN','lin'],
  ['DMX512','dmx'],['RDM (DMX)','rdm'],['IEC 62056-21','iec62056'],['DL/T 645','dlt645'],
  ['G-code','gcode'],['EDID','edid'],['纯文本 Text','text']
];
function ProtocolPanel({sel:selLines,onExpand,onClose}){
  const t=window.tt(React.useContext(window.LangCtx));
  const [sel,setSel]=React.useState('auto');
  const lines=selLines||[];
  const combined=lines.map(l=>l.ascii.replace(/\\r|\\n/g,'').trim()).join(' ');
  const proto=lines.length?(sel==='auto'?detect(combined):sel):null;
  const rows=lines.length?decodeLine(combined,proto):[];
  return <Card title={t('协议解析','DECODE')} style={{height:240,flex:'none'}}
    actions={<>
      {lines.length>0&&onExpand&&<Badge tone="neutral" style={{cursor:'pointer'}} onClick={onExpand} title={t('自动向两侧合并相邻的十六进制分片行','auto-merge adjacent hex fragment lines')}>{t('自动拼帧','AUTO-FRAME')}</Badge>}
      {lines.length>1&&<Badge>{lines.length} {t('行合并','LINES MERGED')}</Badge>}
      {lines.length>0&&<Badge tone="accent">{sel==='auto'?t('自动识别','AUTO')+': '+proto.toUpperCase():proto.toUpperCase()}</Badge>}
      <div style={{width:176}}><Select options={protoList.map(([label,value])=>({value,label}))} value={sel} onChange={e=>setSel(e.target.value)} style={{height:'var(--ctl-h-sm)',fontSize:'var(--fs-label)'}}/></div>
      <IconButton onClick={onClose}>×</IconButton></>}
    bodyStyle={{padding:0,display:'flex',flexDirection:'column',minHeight:0}}>
    {!lines.length&&<div style={{flex:1,display:'flex',alignItems:'center',justifyContent:'center',color:'var(--fg-3)',fontSize:12,padding:14,textAlign:'center'}}>{t('// 点击监视区任意一行，识别设备经串口打印的通讯调试内容，例如 ir code = 0x...、cec rx = 40 44 41','// click any monitor line to decode the communication debug text printed by your device, for example ir code = 0x... or cec rx = 40 44 41')}</div>}
    {lines.length>0&&<div style={{flex:1,overflowY:'auto',minHeight:0}}>
      <div style={{padding:'5px 12px',borderBottom:'1px solid var(--border-0)',fontFamily:'var(--font-mono)',fontSize:'var(--fs-terminal)',display:'flex',gap:10,alignItems:'center'}}>
        <span style={{color:'var(--fg-3)'}}>{lines[0].time}{lines.length>1?' → '+lines[lines.length-1].time:''}</span>
        <Badge tone={lines[0].kind==='tx'?'accent':'ok'}>{lines[0].kind}</Badge>
        <span style={{color:'var(--fg-1)',whiteSpace:'nowrap',overflow:'hidden',textOverflow:'ellipsis'}}>{combined}</span>
      </div>
      <table style={{width:'100%',borderCollapse:'collapse',fontSize:'var(--fs-terminal)',fontFamily:'var(--font-mono)'}}>
        <tbody>{rows.map((r,i)=><tr key={i} style={{borderBottom:'1px solid var(--border-0)'}}>
          <td style={{padding:'3px 12px',color:'var(--fg-3)',whiteSpace:'nowrap',width:150}}>{r.f}</td>
          <td style={{padding:'3px 12px',color:r.err?'var(--err-0)':'var(--accent-0)',whiteSpace:'nowrap'}}>{r.v}</td>
          <td style={{padding:'3px 12px',color:r.err?'var(--err-0)':'var(--fg-2)'}}>{r.m}</td>
        </tr>)}</tbody>
      </table>
    </div>}
  </Card>;
}
window.MrocioaProtocolDecoders={detect,decodeLine,parseHexBytes,protoList};
window.ProtocolPanel=ProtocolPanel;
