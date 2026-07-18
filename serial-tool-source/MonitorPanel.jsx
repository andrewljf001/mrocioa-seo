const {Button,IconButton,Field,Input,Select,Checkbox,Badge,Card,TermLine} = window.MrocioaDesignSystem_b37fe9;
window.LangCtx=window.LangCtx||React.createContext('bi');
window.PrefsCtx=window.PrefsCtx||React.createContext({tsFmt:'abs',fontSz:12,enc:'utf8'});
window.tt=window.tt||(l=>(zh,en)=>l==='zh'?zh:l==='en'?en:zh+' '+en);
const RENDER_MAX=500;
const HEX_RE=/^([0-9A-Fa-f]{2}[\s,]*)+$/;
const parseHex=s=>s.trim().split(/[\s,]+/).map(h=>parseInt(h,16));
const sum8=b=>b.reduce((a,x)=>a+x,0)&0xFF;
const xor8=b=>b.reduce((a,x)=>a^x,0);
const crc16mb=b=>{let c=0xFFFF;for(const x of b){c^=x;for(let i=0;i<8;i++)c=c&1?(c>>1)^0xA001:c>>1;}return c;};
const dl=(content,name,mime)=>{const a=document.createElement('a');a.href=URL.createObjectURL(new Blob(['\ufeff'+content],{type:mime+';charset=utf-8'}));a.download=name;a.click();URL.revokeObjectURL(a.href);};
const stamp=()=>{const d=new Date(),p=n=>String(n).padStart(2,'0');return `${d.getFullYear()}${p(d.getMonth()+1)}${p(d.getDate())}_${p(d.getHours())}${p(d.getMinutes())}${p(d.getSeconds())}`;};
function MonitorPanel({lines,hex,setHex,paused,setPaused,onSend,onClear,connected,onSelect,onDecode,inspected,val,setVal,onSendFile,fileProg,hotkeys,trig}){
  const t=window.tt(React.useContext(window.LangCtx));
  const prefs=React.useContext(window.PrefsCtx);
  const [esc,setEsc]=React.useState(true);
  const [expOpen,setExpOpen]=React.useState(false);
  const [crcOpen,setCrcOpen]=React.useState(false);
  const [txHex,setTxHex]=React.useState(false);
  const [txCrlf,setTxCrlf]=React.useState(true);
  const [multiLine,setMultiLine]=React.useState(false);
  const [query,setQuery]=React.useState('');
  const [filterOnly,setFilterOnly]=React.useState(false);
  const [rec,setRec]=React.useState(false);
  const recFrom=React.useRef(0);
  const hist=React.useRef([]);
  const histIdx=React.useRef(0);
  const searchRef=React.useRef(null);
  const fileRef=React.useRef(null);
  React.useEffect(()=>{if(!expOpen&&!crcOpen)return;const h=()=>{setExpOpen(false);setCrcOpen(false);};window.addEventListener('click',h);return()=>window.removeEventListener('click',h);},[expOpen,crcOpen]);
  React.useEffect(()=>{
    if(!hotkeys)return;
    const h=e=>{
      if(e.ctrlKey&&e.key.toLowerCase()==='l'){e.preventDefault();onClear();}
      if(e.ctrlKey&&e.key.toLowerCase()==='f'){e.preventDefault();searchRef.current&&searchRef.current.focus();}
    };
    window.addEventListener('keydown',h);return()=>window.removeEventListener('keydown',h);
  },[hotkeys,onClear]);
  const logFns=React.useRef({});
  React.useEffect(()=>{
    if(!hotkeys)return;
    const h=e=>{const c=e.detail;if(c==='rec')logFns.current.toggleRec&&logFns.current.toggleRec();else logFns.current.exportLog&&logFns.current.exportLog(c);};
    window.addEventListener('om-log',h);return()=>window.removeEventListener('om-log',h);
  },[hotkeys]);
  const show=l=>{if(hex)return l.hex;return esc?l.ascii:l.ascii.replace(/\\r|\\n/g,'');};
  const ref=React.useRef(null);
  const frozen=React.useRef(null);
  if(paused){if(!frozen.current)frozen.current=lines;}else frozen.current=null;
  const view=paused&&frozen.current?frozen.current:lines;
  const lastIdx=React.useRef(null);
  const q=query.trim().toLowerCase();
  const matches=l=>q&&(show(l).toLowerCase().includes(q));
  const visible=(filterOnly&&q?view.filter(matches):view).slice(-RENDER_MAX);
  const hi=text=>{
    if(!q)return text;
    const lower=text.toLowerCase();const out=[];let pos=0,i,k=0;
    while((i=lower.indexOf(q,pos))>=0){out.push(text.slice(pos,i));out.push(<mark key={k++} style={{background:'var(--accent-dim)',color:'var(--accent-0)',outline:'1px solid var(--border-accent)'}}>{text.slice(i,i+q.length)}</mark>);pos=i+q.length;}
    out.push(text.slice(pos));return out;
  };
  const clickLine=(e,l,i)=>{
    if(e.ctrlKey||e.metaKey)onSelect(inspected.includes(l)?inspected.filter(x=>x!==l):[...inspected,l]);
    else if(e.shiftKey&&lastIdx.current!=null){const [a,b]=[Math.min(lastIdx.current,i),Math.max(lastIdx.current,i)];onSelect(visible.slice(a,b+1));}
    else onSelect(inspected.length===1&&inspected[0]===l?[]:[l]);
    if(!e.shiftKey)lastIdx.current=i;
  };
  const ctxLine=(e,l)=>{e.preventDefault();if(!inspected.includes(l))onSelect([l]);onDecode();};
  React.useEffect(()=>{const el=ref.current;if(el&&!paused)requestAnimationFrame(()=>{el.scrollTop=el.scrollHeight;});},[lines,paused,hex,esc,query,filterOnly]);
  const hexValid=!txHex||val.trim()===''||val.split('\n').every(ln=>ln.trim()===''||HEX_RE.test(ln.trim()));
  const send=()=>{
    if(!val.trim()||!hexValid)return;
    const parts=multiLine?val.split('\n').map(s=>s.trim()).filter(Boolean):[val];
    parts.forEach((p,i)=>setTimeout(()=>onSend(p,{hex:txHex,crlf:txCrlf}),i*80));
    if(hist.current[hist.current.length-1]!==val)hist.current.push(val);
    histIdx.current=hist.current.length;
  };
  const onKey=e=>{
    if(e.key==='Enter')send();
    else if(e.key==='ArrowUp'){e.preventDefault();if(histIdx.current>0){histIdx.current--;setVal(hist.current[histIdx.current]??'');}}
    else if(e.key==='ArrowDown'){e.preventDefault();if(histIdx.current<hist.current.length){histIdx.current++;setVal(hist.current[histIdx.current]??'');}}
  };
  const appendCrc=kind=>{
    if(!hexValid||!val.trim())return;
    const b=parseHex(val);if(b.some(isNaN))return;
    let add;
    if(kind==='sum')add=[sum8(b)];
    else if(kind==='xor')add=[xor8(b)];
    else{const c=crc16mb(b);add=[c&0xFF,c>>8];}
    setVal(val.trim()+' '+add.map(x=>x.toString(16).toUpperCase().padStart(2,'0')).join(' '));
  };
  const stamp2=l=>{const d=new Date(l.tsms||Date.now()),p=n=>String(n).padStart(2,'0');return `${d.getFullYear()}-${p(d.getMonth()+1)}-${p(d.getDate())} ${l.time}`;};
  const exportLog=fmt=>{
    let content,mime,ext;
    if(fmt==='csv'){content='datetime,dir,data\n'+lines.map(l=>`${stamp2(l)},${l.kind},"${(hex?l.hex:l.ascii).replace(/"/g,'""')}"`).join('\n');mime='text/csv';ext='csv';}
    else{content=lines.map(l=>`[${stamp2(l)}] [${l.kind.toUpperCase()}] ${hex?l.hex:l.ascii}`).join('\n');mime='text/plain';ext='txt';}
    dl(content,`serial-log_${stamp()}.${ext}`,mime);
  };
  const recFile=React.useRef(null);
  const recWritten=React.useRef(0);
  React.useEffect(()=>{
    if(!rec||!recFile.current)return;
    const rf=recFile.current;
    const fresh=lines.slice(Math.max(recWritten.current,recFrom.current));
    if(fresh.length){
      recWritten.current=lines.length;
      rf.q=rf.q.then(()=>rf.w.write(fresh.map(l=>`[${stamp2(l)}] [${l.kind.toUpperCase()}] ${l.ascii}`).join('\n')+'\n')).catch(()=>{});
    }
  },[lines,rec]);
  const toggleRec=async()=>{
    if(!rec){
      recFrom.current=lines.length;recWritten.current=lines.length;
      recFile.current=null;
      if(window.showSaveFilePicker){
        try{
          const h=await window.showSaveFilePicker({suggestedName:`serial-rec_${stamp()}.txt`});
          const w=await h.createWritable();
          recFile.current={w,q:Promise.resolve()};
        }catch(e){recFile.current=null;}
      }
      setRec(true);
    }
    else{
      setRec(false);
      if(recFile.current){const rf=recFile.current;recFile.current=null;rf.q.then(()=>rf.w.close()).catch(()=>{});return;}
      const seg=lines.slice(recFrom.current);
      dl(seg.map(l=>`[${stamp2(l)}] [${l.kind.toUpperCase()}] ${l.ascii}`).join('\n'),`serial-rec_${stamp()}.txt`,'text/plain');
    }
  };
  logFns.current={exportLog,toggleRec};
  const trigHit=l=>trig&&trig.on&&trig.kw&&l.kind==='rx'&&l.ascii.includes(trig.kw);
  const timeOf=(l,prev)=>prefs.tsFmt==='off'?undefined:prefs.tsFmt==='rel'?`+${(((l.tsms||0)-(prev?prev.tsms||0:l.tsms||0))/1000).toFixed(3)}s`:l.time;
  const menuStyle={position:'absolute',top:'calc(100% + 4px)',right:0,zIndex:10,background:'var(--surface-card)',border:'1px solid var(--border-accent)',borderRadius:'var(--radius-1)',boxShadow:'0 4px 16px rgba(0,0,0,.5)',minWidth:150,padding:4,display:'flex',flexDirection:'column'};
  const itemStyle={all:'unset',cursor:'pointer',padding:'5px 8px',fontSize:11,fontFamily:'var(--font-mono)',color:'var(--fg-1)',borderRadius:'var(--radius-1)'};
  return <div style={{flex:1,minWidth:0,display:'flex',flexDirection:'column',gap:8}}>
    <Card title={t('数据监视','MONITOR')} scanline style={{flex:1,minHeight:0}}
      actions={<>
        <Badge tone={hex?'accent':'neutral'} style={{cursor:'pointer'}} onClick={()=>setHex(true)}>HEX</Badge>
        <Badge tone={!hex?'accent':'neutral'} style={{cursor:'pointer'}} onClick={()=>setHex(false)}>ASCII</Badge>
        <Badge tone={esc?'accent':'neutral'} style={{cursor:'pointer',textTransform:'none'}} onClick={()=>setEsc(!esc)} title={t('显示/隐藏 \\r\\n 转义符','show/hide \\r\\n')}>\r\n</Badge>
        <IconButton title={paused?t('继续','resume'):t('暂停','pause')} active={paused} onClick={()=>setPaused(!paused)}>{paused?'⏵':'⏸'}</IconButton>
      </>}
      bodyStyle={{overflow:'hidden',display:'flex',flexDirection:'column'}}>
      <div style={{display:'flex',alignItems:'center',gap:6,padding:'0 0 6px',borderBottom:'1px solid var(--border-0)',flex:'none',flexWrap:'wrap'}}>
        <Input ref={searchRef} value={query} onChange={e=>setQuery(e.target.value)} placeholder={t('搜索… Ctrl+F','search… Ctrl+F')} style={{width:130,height:'var(--ctl-h-sm)',fontSize:'var(--fs-label)'}}/>
        {q&&<Badge tone={filterOnly?'accent':'neutral'} style={{cursor:'pointer'}} onClick={()=>setFilterOnly(!filterOnly)} title={t('只显示匹配行','show matches only')}>{t('过滤','FILTER')}</Badge>}
        <div style={{flex:1}}></div>
        <Badge tone={rec?'err':'neutral'} style={{cursor:'pointer'}} onClick={toggleRec} title={t('连续记录到文件（再点一次停止并下载）','record to file (click again to stop & download)')}>{rec?'● REC':'REC'}</Badge>
        <Button size="sm" variant="ghost" onClick={onClear}>{t('清空','CLEAN')}</Button>
        <div style={{position:'relative'}} onClick={e=>e.stopPropagation()}>
          <Button size="sm" variant="ghost" onClick={()=>setExpOpen(o=>!o)}>{t('导出','EXPORT')} ▾</Button>
          {expOpen&&<div style={menuStyle}>
            {[['txt',t('文本日志','Plain log')+' (.txt)'],['csv','CSV (.csv)']].map(([fmt,label])=><button key={fmt} onClick={()=>{exportLog(fmt);setExpOpen(false);}} style={itemStyle} onMouseEnter={e=>e.target.style.background='var(--accent-dim)'} onMouseLeave={e=>e.target.style.background='transparent'}>{label}</button>)}
          </div>}
        </div>
      </div>
      <div ref={ref} style={{flex:1,overflowY:'auto',minHeight:0,fontSize:prefs.fontSz}}>
        {view.length===0&&<div style={{color:'var(--fg-3)',fontSize:12}}>{t('// 未连接任何设备 — 点击 CONNECT 选择串口','// no device — click CONNECT to select a port')}</div>}
        {view.length>RENDER_MAX&&!filterOnly&&<div style={{color:'var(--fg-3)',fontSize:10,padding:'2px 0'}}>{t(`… 仅渲染最近 ${RENDER_MAX} 行，完整 ${lines.length.toLocaleString()} 行在缓冲区，导出可获全部`,`… showing last ${RENDER_MAX} lines; full ${lines.length.toLocaleString()} in buffer, export to get all`)}</div>}
        {paused&&<div style={{color:'var(--warn-0,#ffb454)',fontSize:10,padding:'2px 0'}}>{t(`⏸ 已暂停显示 — 后台继续接收 (+${(lines.length-view.length).toLocaleString()} 行)`,`⏸ display paused — still receiving (+${(lines.length-view.length).toLocaleString()} lines)`)}</div>}
        {filterOnly&&q&&<div style={{color:'var(--accent-0)',fontSize:10,padding:'2px 0'}}>{t(`过滤中：仅显示含 "${query}" 的行 (${visible.length})`,`filtered: lines containing "${query}" (${visible.length})`)}</div>}
        {rec&&<div style={{color:'var(--err-0)',fontSize:10,padding:'2px 0'}}>{t(`● 记录中 — 已记 ${(lines.length-recFrom.current).toLocaleString()} 行，再点 REC 停止并下载`,`● recording — ${(lines.length-recFrom.current).toLocaleString()} lines, click REC to stop & download`)}</div>}
        {visible.map((l,i)=><div key={i} onClick={e=>clickLine(e,l,i)} onContextMenu={e=>ctxLine(e,l)} title={t('点击选择 · Ctrl/Shift 多选 · 右键解析','click to select · Ctrl/Shift multi · right-click to decode')} style={{cursor:'pointer',userSelect:'none',background:inspected&&inspected.includes(l)?'var(--accent-dim)':trigHit(l)?'rgba(255,92,92,.12)':'transparent',borderLeft:inspected&&inspected.includes(l)?'2px solid var(--accent-0)':trigHit(l)?'2px solid var(--err-0)':'2px solid transparent'}}><TermLine kind={l.kind} time={timeOf(l,visible[i-1])}>{hi(show(l))}</TermLine></div>)}
      </div>
    </Card>
    <Card title={t('发送','TRANSMIT')}>
      <div style={{display:'flex',flexDirection:'column',gap:6}}>
        <div style={{display:'flex',alignItems:'center',gap:6,flexWrap:'wrap'}}>
          <Badge tone={txHex?'accent':'neutral'} style={{cursor:'pointer'}} onClick={()=>setTxHex(true)}>HEX</Badge>
          <Badge tone={!txHex?'accent':'neutral'} style={{cursor:'pointer'}} onClick={()=>setTxHex(false)}>ASCII</Badge>
          <Badge tone={txCrlf?'accent':'neutral'} style={{cursor:'pointer',textTransform:'none'}} onClick={()=>setTxCrlf(!txCrlf)} title={t('发送后追加 \\r\\n','append \\r\\n on send')}>+\r\n</Badge>
          <Badge tone={multiLine?'accent':'neutral'} style={{cursor:'pointer'}} onClick={()=>setMultiLine(!multiLine)} title={t('多行模式：逐行发送，Ctrl+Enter 发送','multiline: send line by line, Ctrl+Enter to send')}>{t('多行','MULTI')}</Badge>
          {txHex&&<div style={{position:'relative'}} onClick={e=>e.stopPropagation()}>
            <Badge tone="neutral" style={{cursor:'pointer'}} onClick={()=>setCrcOpen(o=>!o)}>{t('校验','CRC')} ▾</Badge>
            {crcOpen&&<div style={{...menuStyle,right:'auto',left:0}}>
              {[['sum',t('校验和','Checksum-8')],['xor','XOR (BCC)'],['crc16','CRC16-Modbus']].map(([k,label])=><button key={k} onClick={()=>{appendCrc(k);setCrcOpen(false);}} style={itemStyle} onMouseEnter={e=>e.target.style.background='var(--accent-dim)'} onMouseLeave={e=>e.target.style.background='transparent'}>{t('追加','append')+' '+label}</button>)}
            </div>}
          </div>}
        </div>
        <div style={{display:'flex',gap:8}}>
          {multiLine?<textarea value={val} onChange={e=>setVal(e.target.value)} onKeyDown={e=>{if(e.key==='Enter'&&e.ctrlKey)send();}} rows={3} placeholder={connected?t('每行一条，Ctrl+Enter 逐行发送','one per line, Ctrl+Enter to send'):t('// 请先连接串口','// connect a port first')} style={{flex:1,background:'var(--surface-input)',border:`1px solid ${hexValid?'var(--border-1)':'var(--err-0)'}`,borderRadius:'var(--radius-1)',color:'var(--fg-0)',fontFamily:'var(--font-mono)',fontSize:'var(--fs-body)',padding:'6px 8px',outline:'none',resize:'vertical'}}></textarea>
          :<Input value={val} onChange={e=>setVal(e.target.value)} onKeyDown={onKey} placeholder={connected?(txHex?t('输入十六进制，如 AA 55 01 0F…','hex bytes e.g. AA 55 01 0F…'):t('输入待发送数据… ↑↓ 翻历史','type data… ↑↓ history')):t('// 请先连接串口','// connect a port first')} style={{flex:1,borderColor:hexValid?undefined:'var(--err-0)'}}/>}
          <input ref={fileRef} type="file" style={{display:'none'}} onChange={e=>{const f=e.target.files[0];if(f)onSendFile(f);e.target.value='';}}/>
          <Button variant="ghost" disabled={!connected||fileProg!=null} onClick={()=>fileRef.current.click()} title={t('发送文件','send a file')}>{t('文件','FILE')}</Button>
          <Button variant="primary" glow={connected} disabled={!connected||!hexValid} onClick={send}>{t('发送','SEND')}</Button>
        </div>
        {!hexValid&&<div style={{fontSize:10,color:'var(--err-0)'}}>{t('HEX 格式无效 — 需要成对十六进制字节，如 AA 55 0F','invalid hex — need byte pairs like AA 55 0F')}</div>}
        {fileProg!=null&&<div style={{display:'flex',alignItems:'center',gap:8}}>
          <div style={{flex:1,height:4,background:'var(--bg-1)',borderRadius:2,overflow:'hidden'}}><div style={{width:`${Math.round(fileProg*100)}%`,height:'100%',background:'var(--accent-0)',boxShadow:'0 0 8px var(--accent-0)',transition:'width .2s'}}></div></div>
          <span style={{fontSize:10,color:'var(--accent-0)',fontFamily:'var(--font-mono)'}}>{Math.round(fileProg*100)}%</span>
        </div>}
      </div>
    </Card>
  </div>;
}
window.MonitorPanel=MonitorPanel;
