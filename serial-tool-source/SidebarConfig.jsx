const {Button,IconButton,Field,Input,Select,Checkbox,Switch,Badge,StatusLight,Card} = window.MrocioaDesignSystem_b37fe9;
window.LangCtx=window.LangCtx||React.createContext('bi');
window.tt=window.tt||(l=>(zh,en)=>l==='zh'?zh:l==='en'?en:zh+' '+en);
const QS_KEY='mrocioa.quick';
const CHIPS={'1a86:7523':{name:'CH340',max:2000000,clk:12000000,frac:1},'1a86:5523':{name:'CH341',max:2000000,clk:12000000,frac:1},'1a86:55d4':{name:'CH9102',max:4000000,clk:48000000,frac:1},'10c4:ea60':{name:'CP2102',max:1000000,clk:24000000,frac:1},'10c4:ea70':{name:'CP2105',max:2000000,clk:24000000,frac:1},'0403:6001':{name:'FT232R',max:3000000,clk:3000000,frac:8},'0403:6015':{name:'FT231X',max:3000000,clk:3000000,frac:8},'0403:6014':{name:'FT232H',max:12000000,clk:12000000,frac:8},'0403:6010':{name:'FT2232',max:12000000,clk:12000000,frac:8},'067b:2303':{name:'PL2303',max:1228800,clk:12288000,frac:1}};
const nearestBaud=(chip,want)=>{
  if(!chip.clk||!want)return null;
  const steps=chip.clk*chip.frac;
  const n=Math.max(chip.frac,Math.round(steps/want));
  const real=steps/n;
  return {real:Math.round(real),err:Math.abs(real-want)/want*100};
};
const ALL_BAUDS=[9600,19200,38400,57600,115200,230400,460800,921600,1000000,1500000,2000000,3000000,4000000,6000000,8000000,12000000];
const loadQuick=()=>{try{const v=JSON.parse(localStorage.getItem(QS_KEY)||'null');if(Array.isArray(v))return v;}catch(e){}return [['Firmware Version','AT+GMR',false],['Reset','AT+RST',false],['Scan WiFi','AT+CWLAP',false],['Heartbeat','PING',false]];};
function SidebarConfig({connected,onConnect,cfg,setCfg,auto,setAuto,txVal,onSend,onPulse,reply,setReply,trig,setTrig,ports,setPorts,requestPort,otherUsed,guard,setGuard,guardStart,guardStop,sidebar,sigs,onSignals,paneTag,selfLabel,seqCfg,setSeqCfg,seqRun,runSeq,stopSeq,dtr,rts,setDtrS,setRtsS}){
  const tgt={cfg,setCfg,connected,toggle:onConnect,otherUsed,label:selfLabel||'',tag:paneTag||'',primary:paneTag==='MAIN'};
  const lang=React.useContext(window.LangCtx);
  const t=window.tt(lang);
  const ts=(zh,en)=>lang==='en'?en:zh;
  const up=k=>e=>tgt.setCfg(c=>({...c,[k]:e.target.value}));
  const upv=k=>v=>tgt.setCfg(c=>({...c,[k]:typeof v==='boolean'?v:!c[k]}));
  const [items,setItems]=React.useState(loadQuick);
  React.useEffect(()=>{try{localStorage.setItem(QS_KEY,JSON.stringify(items));}catch(e){}},[items]);
  const [adding,setAdding]=React.useState(false);
  const [newN,setNewN]=React.useState('');
  const [newC,setNewC]=React.useState('');
  const [newHex,setNewHex]=React.useState(false);
  const [addErr,setAddErr]=React.useState('');
  const doAdd=()=>{
    const r=sidebar.onAdd();
    if(r&&r.ok===false){setAddErr(r.max?t('已达窗口上限（4 个）','pane limit reached (4)'):t(`无法新增：仅检测到 ${r.total} 个串口，已全部占用`,`cannot add: only ${r.total} ports detected, all in use`));setTimeout(()=>setAddErr(''),3000);}
  };
  const availPorts=ports.filter(e=>e.id===tgt.cfg.port||!(tgt.otherUsed||[]).includes(e.id));
  const tentry=ports.find(e=>e.id===tgt.cfg.port);
  const chipKey=tentry&&tentry.vid?`${tentry.vid.toString(16).padStart(4,'0')}:${(tentry.pid||0).toString(16).padStart(4,'0')}`:null;
  const chip=(chipKey&&CHIPS[chipKey])||{name:null,max:921600};
  const baudOpts=[...ALL_BAUDS.filter(b=>b<=chip.max),{value:'custom',label:ts('自定义…','Custom…')}];
  const customBaudOver=tgt.cfg.baud==='custom'&&+tgt.cfg.baudCustom>chip.max;
  const nb=tgt.cfg.baud==='custom'&&!customBaudOver&&chip.name?nearestBaud(chip,+tgt.cfg.baudCustom):null;
  const portOpts=availPorts.length?availPorts.map(e=>({value:e.id,label:e.label})):[{value:'',label:ts('未授权串口','no port granted')}];
  const [portErr,setPortErr]=React.useState('');
  const doRequest=async()=>{
    const r=await requestPort();
    if(r&&r.err){setPortErr(r.err);setTimeout(()=>setPortErr(''),5000);}
  };
  const setDtrSl=setDtrS;
  const setRtsSl=setRtsS;
  const refreshPorts=()=>setPorts(p=>p.includes('COM9 — CH9102F')?p:[...p,'COM9 — CH9102F']);
  const sig=(on)=>connected&&on?'ok':'off';
  const mainPane=sidebar?{tag:paneTag,label:selfLabel}:null;
  const paneBadge=mainPane?<Badge tone="accent" style={{maxWidth:140,overflow:'hidden',textOverflow:'ellipsis',textTransform:'none'}}>{mainPane.tag} · {mainPane.label}</Badge>:null;
  return <div style={{flex:1,minWidth:0,display:'flex',flexDirection:'column',gap:8,overflowY:'auto',overflowX:'hidden'}}>
    {sidebar&&<Card title={t('串口窗口','PORTS & PANES')}>
      <div style={{display:'flex',flexDirection:'column',gap:2}}>
        {sidebar.panes.map(p=>{const sel=p.id===sidebar.targetId;return <div key={p.id} onClick={()=>sidebar.pick(p.id)} style={{display:'flex',alignItems:'center',gap:6,padding:'4px 6px',borderRadius:'var(--radius-1)',cursor:'pointer',background:sel?'var(--accent-dim)':'transparent',border:`1px solid ${sel?'var(--border-accent)':'transparent'}`}}>
          <StatusLight tone={p.connected?'ok':'off'} pulse={p.connected}/>
          <span style={{fontSize:11,fontFamily:'var(--font-mono)',color:sel?'var(--accent-0)':'var(--fg-1)',flex:1,whiteSpace:'nowrap',overflow:'hidden',textOverflow:'ellipsis'}}>{p.label}</span>
          <Badge tone={p.primary?'accent':'neutral'}>{p.tag}</Badge>
          {!p.primary&&<IconButton title={t('设为主监控','make primary')} onClick={e=>{e.stopPropagation();sidebar.makePrimary(p.id);}}>⇄</IconButton>}
          {sidebar.panes.length>1&&<IconButton title={t('关闭此串口','close')} onClick={e=>{e.stopPropagation();sidebar.close(p.id);}}>×</IconButton>}
        </div>;})}
        <Button size="sm" variant="ghost" disabled={sidebar.canAdd===false} style={{alignSelf:'flex-start',marginTop:2}} onClick={doAdd}>+ {t('新增串口','ADD PORT')}</Button>
        {addErr&&<span style={{fontSize:10,color:'var(--err-0)'}}>{addErr}</span>}
        <span style={{fontSize:9,color:'var(--fg-3)'}}>{t('点选窗口后，下方连接配置对其生效；后台会话照常收发','config below applies to selected; BG sessions keep running')}</span>
      </div>
    </Card>}
    <Card title={t('连接配置','CONNECTION')} actions={sidebar&&<Badge tone="accent" style={{maxWidth:150,overflow:'hidden',textOverflow:'ellipsis'}}>{tgt.tag} · {tgt.label}</Badge>}>
      <div style={{display:'flex',flexDirection:'column',gap:8}}>
        {ports.length===0?<div style={{display:'flex',flexDirection:'column',gap:6,padding:'10px 8px',border:'1px dashed var(--border-accent)',borderRadius:'var(--radius-1)',background:'var(--accent-dim)'}}>
          <span style={{fontSize:11,color:'var(--accent-0)',fontWeight:600}}>{ts('首次使用：请先授权串口','First time: grant port access')}</span>
          <span style={{fontSize:10,color:'var(--fg-2)',lineHeight:1.5}}>{ts('浏览器安全限制，每台设备首次需手动授权一次，之后插入即自动识别','browser requires one-time manual grant per device; auto-detected afterwards')}</span>
          <Button variant="primary" glow size="lg" onClick={doRequest} style={{whiteSpace:'nowrap',overflow:'hidden',textOverflow:'ellipsis',minWidth:0}}>🔌 {ts('扫描并授权串口','SCAN & GRANT PORT')}</Button>
          {portErr&&<span style={{fontSize:10,color:'var(--err-0)'}}>{portErr}</span>}
        </div>
        :<Field label={t('串口','Port')}>
          <div style={{display:'flex',gap:4}}>
            <Select options={portOpts} value={tgt.cfg.port} onChange={up('port')} disabled={tgt.connected} style={{flex:1,minWidth:0}}/>
            <Button size="sm" variant="ghost" onClick={doRequest} title={t('授权新串口 requestPort()','grant a new port')} style={{flex:'none',color:'var(--accent-0)',borderColor:'var(--border-accent)'}}>+ {ts('授权','GRANT')}</Button>
          </div>
          {portErr&&<span style={{fontSize:10,color:'var(--err-0)'}}>{portErr}</span>}
        </Field>}
        <Field label={t('波特率','Baud Rate')+(chip.name?` · ${chip.name} ≤${chip.max>=1000000?(chip.max/1000000)+'M':chip.max}`:'')}><Select options={baudOpts} value={tgt.cfg.baud} onChange={up('baud')} disabled={tgt.connected}/></Field>
        {tgt.cfg.baud==='custom'&&<Field label={t('自定义波特率','Custom baud')}>
          <Input value={tgt.cfg.baudCustom} onChange={up('baudCustom')} disabled={tgt.connected} placeholder="250000" invalid={customBaudOver}/>
          {customBaudOver&&<span style={{fontSize:10,color:'var(--err-0)'}}>{ts(`超过 ${chip.name||'芯片'} 上限 ${chip.max.toLocaleString()}`,`exceeds ${chip.name||'chip'} limit ${chip.max.toLocaleString()}`)}</span>}
          {nb&&nb.err>0.05&&<span style={{fontSize:10,color:nb.err>3?'var(--err-0)':'var(--warn-0,#ffb454)'}}>{ts(`${chip.name} 实际可达≈${nb.real.toLocaleString()}（偏差 ${nb.err.toFixed(1)}%${nb.err>3?'，会乱码，建议改用标准档':''}）`,`${chip.name} nearest ≈${nb.real.toLocaleString()} (${nb.err.toFixed(1)}% off${nb.err>3?' — will corrupt, use a standard rate':''})`)}</span>}
          {nb&&nb.err<=0.05&&+tgt.cfg.baudCustom>0&&<span style={{fontSize:10,color:'var(--ok-0)'}}>{ts(`${chip.name} 可精确分频 ✓`,`${chip.name} exact divisor ✓`)}</span>}
        </Field>}
        <div style={{display:'grid',gridTemplateColumns:'minmax(0,1fr) minmax(0,1fr) minmax(0,1fr)',gap:6}}>
          <Field label={ts('数据位','Data')}><Select options={[8,7,6,5]} value={tgt.cfg.dataBits} onChange={up('dataBits')} disabled={tgt.connected}/></Field>
          <Field label={ts('校验','Parity')}><Select options={[{value:'N',label:'None'},{value:'E',label:'Even'},{value:'O',label:'Odd'}]} value={tgt.cfg.parity} onChange={up('parity')} disabled={tgt.connected}/></Field>
          <Field label={ts('停止位','Stop')}><Select options={[1,2]} value={tgt.cfg.stopBits} onChange={up('stopBits')} disabled={tgt.connected}/></Field>
        </div>
        <div style={{display:'grid',gridTemplateColumns:'minmax(0,1fr) minmax(0,1fr)',gap:6}}>
          <Field label={ts('流控','Flow')}><Select options={[{value:'none',label:'None'},{value:'RTS/CTS',label:'RTS/CTS'}]} value={cfg.flow==='XON/XOFF'?'none':cfg.flow} onChange={up('flow')} disabled={tgt.connected}/></Field>
          <Field label={ts('分帧','Framing')}><Select options={[{value:'crlf',label:'\\r\\n'},{value:'lf',label:'\\n'},{value:'timeout',label:ts('超时 20ms','20ms idle')}]} value={tgt.cfg.framing} onChange={up('framing')}/></Field>
        </div>
        <div style={{display:'grid',gridTemplateColumns:'minmax(0,1fr) minmax(0,1fr)',gap:6,marginTop:2}}>
          <div style={{display:'flex',alignItems:'center',gap:6}}><Switch label="DTR" checked={dtr} onChange={setDtrS}/><Button size="sm" variant="ghost" disabled={!tgt.connected} onClick={()=>onPulse('DTR')} title={ts('DTR 低脉冲 100ms（复位 MCU）','pulse DTR low 100ms')}>⚡</Button></div>
          <div style={{display:'flex',alignItems:'center',gap:6}}><Switch label="RTS" checked={rts} onChange={setRtsS}/><Button size="sm" variant="ghost" disabled={!tgt.connected} onClick={()=>onPulse('RTS')} title={ts('RTS 低脉冲 100ms','pulse RTS low 100ms')}>⚡</Button></div>
        </div>
        <div style={{display:'flex',gap:12,alignItems:'center',padding:'4px 0',borderTop:'1px solid var(--border-0)',borderBottom:'1px solid var(--border-0)'}}>
          <span style={{fontSize:9,color:'var(--fg-3)',letterSpacing:'.08em',whiteSpace:'nowrap'}}>{ts('信号','LINES')}</span>
          <StatusLight tone={sigs&&sigs.cts?'ok':'off'} label="CTS"/><StatusLight tone={sigs&&sigs.dsr?'ok':'off'} label="DSR"/><StatusLight tone={sigs&&sigs.ri?'ok':'off'} label="RI"/><StatusLight tone={sigs&&sigs.dcd?'ok':'off'} label="DCD"/>
        </div>
        <div style={{display:'flex',justifyContent:'space-between',alignItems:'center'}}>
          <span style={{fontSize:10,color:'var(--fg-3)',whiteSpace:'nowrap'}}>{ts('断线自动重连','Auto reconnect')}</span>
          <Switch checked={!!tgt.cfg.autoRe} onChange={upv('autoRe')}/>
        </div>
        <Button variant={tgt.connected?'danger':'primary'} glow={!tgt.connected} size="lg" onClick={tgt.toggle} style={{marginTop:4}}>{tgt.connected?t('断开','DISCONNECT'):t('连接','CONNECT')}</Button>
      </div>
    </Card>
    <Card title={t('快捷发送','QUICK SEND')} actions={paneBadge}>
      <div style={{display:'flex',flexDirection:'column',gap:4}}>
        {items.map(([n,c,isHex],i)=>
          <div key={i} style={{display:'flex',alignItems:'center',gap:6}}>
            <Button size="sm" variant="ghost" style={{flex:'none'}} disabled={!connected} onClick={()=>onSend(c,{hex:!!isHex})} title={t('发送','send')+' '+c}>⏵</Button>
            <span style={{fontSize:11,color:'var(--fg-1)',flex:1,whiteSpace:'nowrap',overflow:'hidden',textOverflow:'ellipsis'}}>{n}</span>
            {isHex&&<Badge>HEX</Badge>}
            <span style={{fontSize:10,color:'var(--fg-3)',whiteSpace:'nowrap',overflow:'hidden',textOverflow:'ellipsis',maxWidth:80}}>{c}</span>
            <IconButton title={t('删除','remove')} onClick={()=>setItems(it=>it.filter((_,j)=>j!==i))}>×</IconButton>
          </div>)}
        {adding?<div style={{display:'flex',flexDirection:'column',gap:4,marginTop:2}}>
          <Input value={newN} onChange={e=>setNewN(e.target.value)} placeholder={t('名称','name')}/>
          <Input value={newC} onChange={e=>setNewC(e.target.value)} placeholder={newHex?'AA 55 01 0F':t('命令','command')}/>
          <div style={{display:'flex',gap:6,alignItems:'center'}}>
            <Button size="sm" variant="primary" disabled={!newN.trim()||!newC.trim()} onClick={()=>{setItems(it=>[...it,[newN.trim(),newC.trim(),newHex]]);setNewN('');setNewC('');setNewHex(false);setAdding(false);}}>{t('确定','OK')}</Button>
            <Button size="sm" variant="ghost" onClick={()=>setAdding(false)}>{t('取消','CANCEL')}</Button>
            <Badge tone={newHex?'accent':'neutral'} style={{cursor:'pointer'}} onClick={()=>setNewHex(!newHex)}>HEX</Badge>
          </div>
        </div>
        :<Button size="sm" variant="ghost" style={{alignSelf:'flex-start',marginTop:2}} onClick={()=>setAdding(true)}>+ {t('添加','ADD')}</Button>}
      </div>
    </Card>
    <Card title={t('发送序列','SEQUENCE')} actions={paneBadge}>
      <div style={{display:'flex',flexDirection:'column',gap:6}}>
        <textarea value={seqCfg.text} onChange={e=>setSeqCfg(s=>({...s,text:e.target.value}))} rows={3} placeholder={t('每行一条命令，按顺序发送','one command per line')} style={{background:'var(--surface-input)',border:'1px solid var(--border-1)',borderRadius:'var(--radius-1)',color:'var(--fg-0)',fontFamily:'var(--font-mono)',fontSize:'var(--fs-body)',padding:'6px 8px',outline:'none',resize:'vertical',width:'100%',boxSizing:'border-box'}}></textarea>
        <div style={{display:'flex',alignItems:'center',gap:8}}>
          <Badge tone={!seqCfg.loop?'accent':'neutral'} style={{cursor:'pointer'}} onClick={()=>setSeqCfg(s=>({...s,loop:false}))}>{ts('单次','ONCE')}</Badge>
          <Badge tone={seqCfg.loop?'accent':'neutral'} style={{cursor:'pointer'}} onClick={()=>setSeqCfg(s=>({...s,loop:true}))}>{ts('循环','LOOP')}</Badge>
          {seqRun&&seqRun.loop&&<span style={{fontSize:10,color:'var(--accent-0)',fontFamily:'var(--font-mono)'}}>{ts('第','round ')}{seqRun.round}{ts(' 轮','')}</span>}
        </div>
        <div style={{display:'grid',gridTemplateColumns:'minmax(0,1fr) minmax(0,1fr)',gap:6}}>
          <Field label={ts('行间隔','Cmd gap')+' (ms)'}><Input value={seqCfg.ms} onChange={e=>setSeqCfg(s=>({...s,ms:e.target.value}))}/></Field>
          {seqCfg.loop?<Field label={ts('整组重复','Loop every')+' (ms)'}><Input value={seqCfg.loopMs} onChange={e=>setSeqCfg(s=>({...s,loopMs:e.target.value}))}/></Field>:<div></div>}
        </div>
        {seqRun?<Button variant="danger" onClick={stopSeq}>{t('停止','STOP')} {seqRun.loop?`R${seqRun.round} · `:''}{seqRun.i}/{seqRun.n}</Button>
        :<Button variant="primary" disabled={!connected||!seqCfg.text.trim()} onClick={runSeq}>{seqCfg.loop?t('循环运行','RUN LOOP'):t('运行一次','RUN ONCE')}</Button>}
      </div>
    </Card>
    <Card title={t('自动发送','AUTO SEND')} actions={paneBadge}>
      <div style={{display:'flex',flexDirection:'column',gap:8}}>
        <Field label={ts('发送内容','Payload')}><Input value={auto.text??''} onChange={e=>setAuto(a=>({...a,text:e.target.value}))} placeholder="AT+GMR"/></Field>
        <div style={{display:'flex',alignItems:'flex-end',gap:6}}>
          <Field label={ts('间隔','Interval')+' (ms)'} style={{flex:1}}><Input value={auto.ms} onChange={e=>setAuto(a=>({...a,ms:e.target.value}))}/></Field>
          <Switch label={t('启用','ON')} checked={auto.on} onChange={v=>setAuto(a=>({...a,on:typeof v==='boolean'?v:!a.on}))} disabled={!connected}/>
        </div>
        <div style={{fontSize:10,color:auto.on?'var(--accent-0)':'var(--fg-3)',fontFamily:'var(--font-mono)',whiteSpace:'nowrap',overflow:'hidden',textOverflow:'ellipsis'}}>
          {auto.on?t(`每 ${Math.max(50,+auto.ms||1000)} ms 发送：${auto.text||'(空)'}`,`every ${Math.max(50,+auto.ms||1000)} ms: ${auto.text||'(empty)'}`)
          :connected?t('将按间隔循环发送上方内容','repeats the payload above')
          :t('// 请先连接串口','// connect a port first')}
        </div>
      </div>
    </Card>
    <Card title={t('自动应答','AUTO REPLY')} actions={paneBadge}>
      <div style={{display:'flex',flexDirection:'column',gap:6}}>
        <div style={{display:'grid',gridTemplateColumns:'minmax(0,1fr) minmax(0,1fr)',gap:6}}>
          <Field label={t('匹配收到','When RX has')}><Input value={reply.match} onChange={e=>setReply(r=>({...r,match:e.target.value}))}/></Field>
          <Field label={t('自动回复','Reply with')}><Input value={reply.resp} onChange={e=>setReply(r=>({...r,resp:e.target.value}))}/></Field>
        </div>
        <div style={{display:'flex',justifyContent:'space-between',alignItems:'center'}}>
          <span style={{fontSize:10,color:'var(--fg-3)'}}>{t('收到匹配内容时自动发送','auto-send on match')}</span>
          <Switch checked={reply.on} onChange={v=>setReply(r=>({...r,on:typeof v==='boolean'?v:!r.on}))} disabled={!connected}/>
        </div>
      </div>
    </Card>
    <Card title={t('无人值守','UNATTENDED')} actions={paneBadge}>
      <div style={{display:'flex',flexDirection:'column',gap:6}}>
        <Field label={t('触发关键字','Trigger keyword')}><Input value={guard.kw} onChange={e=>setGuard(s=>({...s,kw:e.target.value}))} disabled={guard.on}/></Field>
        <div style={{display:'grid',gridTemplateColumns:'minmax(0,1fr) minmax(0,1fr)',gap:6}}>
          <Field label={t('记录范围','Capture')}><Select options={[{value:'hits',label:t('命中±10行','hits±10 lines')},{value:'all',label:t('全部','everything')}]} value={guard.mode} onChange={e=>setGuard(s=>({...s,mode:e.target.value}))} disabled={guard.on}/></Field>
          <Field label={t('导出格式','Format')}><Select options={[{value:'txt',label:'.txt'},{value:'csv',label:'.csv'}]} value={guard.fmt} onChange={e=>setGuard(s=>({...s,fmt:e.target.value}))} disabled={guard.on}/></Field>
        </div>
        {guard.on&&<div style={{fontSize:10,fontFamily:'var(--font-mono)',color:'var(--fg-2)',display:'flex',flexDirection:'column',gap:2}}>
          <span>{t('运行','uptime')} <span style={{color:'var(--accent-0)'}}>{(()=>{const s=guard.tick,p=n=>String(n).padStart(2,'0');return `${p(Math.floor(s/3600))}:${p(Math.floor(s/60)%60)}:${p(s%60)}`;})()}</span> · {t('命中','hits')} <span style={{color:guard.hits?'var(--err-0)':'var(--fg-1)'}}>{guard.hits}</span> · {t('已记','buffered')} {guard.buffered.toLocaleString()} {t('行','ln')}</span>
          <span>{t('防待机','wake lock')}: {guard.wake==='ok'?<span style={{color:'var(--ok-0)'}}>✓ {t('屏幕保持唤醒','screen kept awake')}</span>:<span style={{color:'var(--warn-0,#ffb454)'}}>{t('不可用，请在系统设置关闭自动睡眠','unavailable — disable OS sleep')}</span>}</span>
        </div>}
        {guard.on?<Button variant="danger" onClick={guardStop}>{t('停止并导出','STOP & EXPORT')}</Button>
        :<Button variant="primary" disabled={!connected||!guard.kw.trim()} onClick={guardStart}>{t('开始长时间抓取','START CAPTURE')}</Button>}
        {!guard.on&&<span style={{fontSize:9,color:'var(--fg-3)'}}>{t('长时间挂机抓异常：含日期时间戳，自动阻止系统待机，停止时导出文件','long-run capture: full timestamps, blocks system sleep, exports on stop')}</span>}
      </div>
    </Card>
    <Card title={t('触发告警','TRIGGER')} actions={paneBadge}>
      <div style={{display:'flex',flexDirection:'column',gap:6}}>
        <div style={{display:'flex',alignItems:'flex-end',gap:6}}>
          <Field label={t('关键字','Keyword')} style={{flex:1}}><Input value={trig.kw} onChange={e=>setTrig(r=>({...r,kw:e.target.value}))}/></Field>
          <Switch checked={trig.on} onChange={v=>setTrig(r=>({...r,on:typeof v==='boolean'?v:!r.on}))}/>
        </div>
        <div style={{display:'flex',justifyContent:'space-between',alignItems:'center'}}>
          <span style={{fontSize:10,color:'var(--fg-3)'}}>{t('匹配行高亮，可选提示音','highlight matches, optional beep')}</span>
          <Badge tone={trig.beep?'accent':'neutral'} style={{cursor:'pointer'}} onClick={()=>setTrig(r=>({...r,beep:!r.beep}))}>♪ {trig.beep?'ON':'OFF'}</Badge>
        </div>
      </div>
    </Card>
  </div>;
}
window.SidebarConfig=SidebarConfig;
