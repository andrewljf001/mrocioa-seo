const {Button,IconButton,Badge,StatusLight,Tabs,Card,Select} = window.MrocioaDesignSystem_b37fe9;
window.LangCtx=window.LangCtx||React.createContext('bi');
window.PrefsCtx=window.PrefsCtx||React.createContext({tsFmt:'abs',fontSz:12,enc:'utf8'});
window.tt=window.tt||(l=>(zh,en)=>l==='zh'?zh:l==='en'?en:zh+' '+en);
const BUF_MAX=100000;
const SER=navigator.serial;
const WT=(()=>{
  if(typeof Worker==='undefined')return null;
  try{
    const blob=new Blob(["const m={};onmessage=e=>{const{cmd,id,ms,rep}=e.data;if(cmd==='clear'){clearTimeout(m[id]);clearInterval(m[id]);delete m[id];}else{m[id]=rep?setInterval(()=>postMessage(id),ms):setTimeout(()=>{postMessage(id);delete m[id];},ms);}};"],{type:'text/javascript'});
    const w=new Worker(URL.createObjectURL(blob));
    const cbs={};let seq=1;
    w.onmessage=e=>{const cb=cbs[e.data];cb&&cb();};
    return {
      setInterval:(fn,ms)=>{const id=seq++;cbs[id]=fn;w.postMessage({cmd:'set',id,ms,rep:1});return id;},
      setTimeout:(fn,ms)=>{const id=seq++;cbs[id]=()=>{delete cbs[id];fn();};w.postMessage({cmd:'set',id,ms,rep:0});return id;},
      clear:id=>{delete cbs[id];w.postMessage({cmd:'clear',id});}
    };
  }catch(e){return null;}
})();
const wSetInterval=(fn,ms)=>WT?WT.setInterval(fn,ms):setInterval(fn,ms);
const wClearInterval=id=>WT?WT.clear(id):clearInterval(id);
const wSetTimeout=(fn,ms)=>WT?WT.setTimeout(fn,ms):setTimeout(fn,ms);
const wClearTimeout=id=>WT?WT.clear(id):clearTimeout(id);
const fmtDay=ms=>{const d=new Date(ms),p=n=>String(n).padStart(2,'0');return `${d.getFullYear()}-${p(d.getMonth()+1)}-${p(d.getDate())}`;};
let portSeq=1;
const mkEntry=p=>{const info=p.getInfo?p.getInfo():{};const label=`#${portSeq} ${info.usbVendorId?`USB-${info.usbVendorId.toString(16).toUpperCase().padStart(4,'0')}:${(info.usbProductId||0).toString(16).toUpperCase().padStart(4,'0')}`:'SERIAL'}`;portSeq++;return {id:'p'+(portSeq-1),label,port:p,vid:info.usbVendorId,pid:info.usbProductId};};
const now=()=>{const d=new Date();const p=(n,l=2)=>String(n).padStart(l,'0');return `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}.${p(d.getMilliseconds(),3)}`};
const toHex=s=>[...s].map(c=>c.charCodeAt(0).toString(16).toUpperCase().padStart(2,'0')).join(' ');
const RX_POOL=[];
const bd=c=>c.baud==='custom'?(c.baudCustom||'115200'):c.baud;
const parseVals=(raw,fmt)=>{
  const out={};const body=raw.replace(/\\r|\\n/g,'');
  const tryJson=()=>{const m=body.match(/\{.*\}/);if(!m)return false;try{const o=JSON.parse(m[0]);for(const[k,v]of Object.entries(o))if(typeof v==='number')out[k]=v;return Object.keys(out).length>0;}catch(e){return false;}};
  const tryKv=()=>{let f=false;for(const m of body.matchAll(/([A-Za-z_]\w*)\s*=\s*(-?\d+\.?\d*)/g)){out[m[1]]=+m[2];f=true;}return f;};
  const tryCsv=()=>{const p=body.split(',').map(x=>x.trim());if(p.length<2||!p.every(x=>/^-?\d+\.?\d*$/.test(x)))return false;p.forEach((x,i)=>out['ch'+(i+1)]=+x);return true;};
  if(fmt==='json')tryJson();else if(fmt==='kv')tryKv();else if(fmt==='csv')tryCsv();else(tryJson()||tryKv()||tryCsv());
  return out;
};
let audioCtx=null;
const beep=()=>{try{audioCtx=audioCtx||new (window.AudioContext||window.webkitAudioContext)();const o=audioCtx.createOscillator(),g=audioCtx.createGain();o.frequency.value=880;g.gain.value=.08;o.connect(g);g.connect(audioCtx.destination);o.start();o.stop(audioCtx.currentTime+.12);}catch(e){}};
function Session({cfg,setCfg,connected,setConnected,visible,ports,setPorts,requestPort,otherUsed,mode,pos,onSwap,onTarget,isTarget,sidebarMeta,paneTag}){
  const t=window.tt(React.useContext(window.LangCtx));
  const prefs=React.useContext(window.PrefsCtx);
  const [lines,setLines]=React.useState([]);
  const [hex,setHex]=React.useState(false);
  const [paused,setPaused]=React.useState(false);
  const [plot,setPlot]=React.useState(true);
  const [decode,setDecode]=React.useState(false);
  const [inspected,setInspected]=React.useState([]);
  const [txVal,setTxVal]=React.useState('AT+GMR');
  const [auto,setAuto]=React.useState({on:false,ms:1000,text:'AT+GMR'});
  const [seqCfg,setSeqCfg]=React.useState({text:'AT\nAT+GMR\nAT+CWLAP',ms:'800',loop:false,loopMs:'5000'});
  const [seqRun,setSeqRun]=React.useState(null);
  const seqTimer=React.useRef(null);
  const [dtr,setDtrRaw]=React.useState(true);
  const [rts,setRtsRaw]=React.useState(false);
  const [reply,setReply]=React.useState({on:false,match:'ERROR',resp:'AT+RST'});
  const [trig,setTrig]=React.useState({on:false,kw:'ERROR',beep:true});
  const [guard,setGuard]=React.useState({on:false,kw:'ERROR',mode:'hits',fmt:'txt',hits:0,buffered:0,startAt:null,wake:'',tick:0});
  const [sigs,setSigs]=React.useState({});
  const guardRef=React.useRef(guard);guardRef.current=guard;
  const guardBuf=React.useRef({buf:[],ring:[],pending:0});
  const guardFile=React.useRef(null);
  const recoverN=React.useRef(0);
  const wakeRef=React.useRef(null);
  const replyRef=React.useRef(reply);replyRef.current=reply;
  const trigRef=React.useRef(trig);trigRef.current=trig;
  const cfgRef=React.useRef(cfg);cfgRef.current=cfg;
  const [stats,setStats]=React.useState({tx:0,rx:0});
  const [samples,setSamples]=React.useState([]);
  const [channels,setChannels]=React.useState([]);
  const [chartFmt,setChartFmt]=React.useState('auto');
  const chartFmtRef=React.useRef(chartFmt);chartFmtRef.current=chartFmt;
  const [fileProg,setFileProg]=React.useState(null);
  const [sbW,setSbW]=React.useState(264);
  const [chW,setChW]=React.useState(340);
  const sensor=React.useRef({t:23.5,h:61.2,v:3.31,i:142});
  const io=React.useRef({open:false});
  const entryOf=()=>ports.find(p=>p.id===cfgRef.current.port);
  const recordGuard=line=>{
    const g=guardRef.current;if(!g.on)return;
    const gb=guardBuf.current,CTX=10,MAXBUF=200000;
    const gw=guardFile.current;
    const wLine=r=>gw&&(gw.q=gw.q.then(()=>gw.w.write(g.fmt==='csv'?`${fmtDay(r.tsms)} ${r.time},${r.kind},${g.kw&&r.kind==='rx'&&r.ascii.includes(g.kw)?1:0},"${r.ascii.replace(/"/g,'""')}"\n`:`[${fmtDay(r.tsms)} ${r.time}] [${r.kind.toUpperCase()}]${g.kw&&r.kind==='rx'&&r.ascii.includes(g.kw)?' [HIT]':''} ${r.ascii}\n`)).catch(()=>{}));
    const wSep=at=>gw&&(gw.q=gw.q.then(()=>gw.w.write(g.fmt==='csv'?'':`\n===== HIT @ ${fmtDay(at)} ${new Date(at).toTimeString().slice(0,8)} =====\n`)).catch(()=>{}));
    if(g.mode==='all'){gb.buf.push(line);if(gb.buf.length>MAXBUF)gb.buf.shift();wLine(line);setGuard(s=>({...s,buffered:gb.buf.length}));return;}
    gb.ring.push(line);if(gb.ring.length>CTX+1)gb.ring.shift();
    const isHit=g.kw&&line.kind==='rx'&&line.ascii.includes(g.kw);
    if(isHit){
      gb.buf.push({sep:true,at:Date.now()});wSep(Date.now());
      gb.ring.forEach(l=>{gb.buf.push(l);wLine(l);});gb.ring=[];
      gb.pending=CTX;
      setGuard(s=>({...s,hits:s.hits+1,buffered:gb.buf.length}));
    }else if(gb.pending>0){gb.buf.push(line);wLine(line);gb.pending--;setGuard(s=>({...s,buffered:gb.buf.length}));}
  };
  const push=(kind,ascii,hexOv)=>{
    const line={kind,time:now(),tsms:Date.now(),ascii,hex:hexOv||(kind==='sys'||kind==='err'?ascii:toHex(ascii.replace(/\\r\\n/g,'\r\n')))};
    setLines(ls=>[...ls.slice(-(BUF_MAX-1)),line]);
    recordGuard(line);
  };
  const feedChart=ascii=>{
    const vals=parseVals(ascii,chartFmtRef.current);
    const keys=Object.keys(vals);
    if(!keys.length)return;
    setChannels(ch=>{const add=keys.filter(k=>!ch.includes(k));return add.length?[...ch,...add]:ch;});
    setSamples(ss=>[...ss.slice(-299),vals]);
  };
  const handleRx=ascii=>{
    push('rx',ascii);setStats(st=>({...st,rx:st.rx+ascii.length}));feedChart(ascii);
    const tr=trigRef.current;
    if(tr.on&&tr.kw&&ascii.includes(tr.kw)&&tr.beep)beep();
    const rr=replyRef.current;
    if(rr.on&&rr.match&&ascii.includes(rr.match)&&rr.resp)setTimeout(()=>{onSend(rr.resp);},150);
  };
  // ---- real serial I/O ----
  const flushFrames=()=>{
    const g=io.current;
    if(cfgRef.current.framing==='timeout'){
      clearTimeout(g.tmr);
      g.tmr=setTimeout(()=>{if(g.buf){handleRx(g.buf);g.buf='';}},20);
      return;
    }
    const sep=cfgRef.current.framing==='lf'?'\n':'\r\n';
    let idx;
    while((idx=g.buf.indexOf(sep))>=0){
      const line=g.buf.slice(0,idx);
      g.buf=g.buf.slice(idx+sep.length);
      if(line)handleRx(line);
    }
    if(g.buf.length>65536){handleRx(g.buf);g.buf='';}
  };
  const readLoop=async port=>{
    let dec;
    try{dec=new TextDecoder(prefs.enc==='gbk'?'gbk':prefs.enc==='latin1'?'latin1':'utf-8');}catch(e){dec=new TextDecoder();}
    while(io.current.keep&&port.readable){
      const reader=port.readable.getReader();
      io.current.reader=reader;
      try{
        while(io.current.keep){
          const {value,done}=await reader.read();
          if(done)break;
          if(value&&value.length){
            io.current.buf+=dec.decode(value,{stream:true});
            flushFrames();
          }
        }
      }catch(e){if(io.current.keep){push('err',t('读取错误','read error')+': '+e.message);io.current.err=true;}}
      finally{try{reader.releaseLock();}catch(e){}}
      if(!io.current.keep)break;
    }
    if(io.current.keep&&io.current.err&&cfgRef.current.autoRe&&recoverN.current<3){
      recoverN.current++;
      push('sys',t(`1 秒后尝试自动恢复 (${recoverN.current}/3)`,`auto-recovering in 1s (${recoverN.current}/3)`));
      wSetTimeout(async()=>{
        await closeReal();
        const en=entryOf();
        if(en){const ok=await openReal(en);if(ok)recoverN.current=0;else setConnected(false);}
        else setConnected(false);
      },1000);
    }
  };
  const openReal=async entry=>{
    try{
      await entry.port.open({baudRate:parseInt(bd(cfg))||115200,dataBits:+cfg.dataBits,stopBits:+cfg.stopBits,parity:cfg.parity==='N'?'none':cfg.parity==='E'?'even':'odd',flowControl:cfg.flow==='RTS/CTS'?'hardware':'none',bufferSize:65536});
      io.current={open:true,keep:true,entry,port:entry.port,buf:''};
      io.current.writer=entry.port.writable.getWriter();
      readLoop(entry.port);
      return true;
    }catch(e){push('err',`${t('打开失败','open failed')}: ${e.message}`);return false;}
  };
  const closeReal=async()=>{
    const g=io.current;if(!g.open)return;
    g.keep=false;clearTimeout(g.tmr);
    try{g.reader&&await g.reader.cancel();}catch(e){}
    try{g.writer&&g.writer.releaseLock();}catch(e){}
    try{g.port&&await g.port.close();}catch(e){}
    io.current={open:false};
  };
  React.useEffect(()=>{
    const entry=entryOf();
    if(connected&&!entry){push('err',t('未选择串口 — 点 ⟳ 授权','no port selected — click ⟳ to grant access'));setConnected(false);return;}
    if(connected&&entry&&!io.current.open){
      openReal(entry).then(ok=>{if(!ok)setConnected(false);});
    }
    if(!connected&&io.current.open)closeReal();
  },[connected]);
  React.useEffect(()=>()=>{closeReal();},[]);
  React.useEffect(()=>{
    if(!SER)return;
    const onDis=e=>{
      if(io.current.open&&io.current.port===e.target){
        push('err',t('设备已断开/拔出 device lost','device disconnected/unplugged'));
        io.current.lost=e.target;
        closeReal();setConnected(false);
      }
    };
    const onCon=e=>{
      if(cfgRef.current.autoRe&&io.current.lost===e.target){
        push('sys',t('设备重新接入，自动重连…','device back, auto-reconnecting…'));
        io.current.lost=null;
        setTimeout(()=>setConnected(true),300);
      }
    };
    SER.addEventListener('disconnect',onDis);SER.addEventListener('connect',onCon);
    return()=>{SER.removeEventListener('disconnect',onDis);SER.removeEventListener('connect',onCon);};
  },[]);
  React.useEffect(()=>{
    if(!connected)return;
    const iv=setInterval(async()=>{try{const s=await io.current.port.getSignals();setSigs({cts:s.clearToSend,dsr:s.dataSetReady,ri:s.ringIndicator,dcd:s.dataCarrierDetect});}catch(e){}},1000);
    return()=>{clearInterval(iv);setSigs({});};
  },[connected]);
  const onSignals=async o=>{
    if(connected&&io.current.open){try{await io.current.port.setSignals({dataTerminalReady:!!o.dtr,requestToSend:!!o.rts});}catch(e){push('err','setSignals: '+e.message);}}
  };
  const setDtrS=v=>{const nv=typeof v==='boolean'?v:!dtr;setDtrRaw(nv);onSignals({dtr:nv,rts});};
  const setRtsS=v=>{const nv=typeof v==='boolean'?v:!rts;setRtsRaw(nv);onSignals({dtr,rts:nv});};
  const stopSeq=()=>{wClearTimeout(seqTimer.current);setSeqRun(null);};
  const runSeq=()=>{
    const cmds=seqCfg.text.split('\n').map(s=>s.trim()).filter(Boolean);
    if(!cmds.length||!connected)return;
    const ms=Math.max(100,+seqCfg.ms||800);
    const loopMs=Math.max(200,+seqCfg.loopMs||5000);
    const loop=seqCfg.loop;
    let i=0,round=1;
    const step=()=>{
      onSend(cmds[i]);i++;
      setSeqRun({i,n:cmds.length,round,loop});
      if(i<cmds.length)seqTimer.current=wSetTimeout(step,ms);
      else if(loop){i=0;round++;seqTimer.current=wSetTimeout(step,loopMs);}
      else seqTimer.current=wSetTimeout(()=>setSeqRun(null),600);
    };
    step();
  };
  React.useEffect(()=>{if(!connected)stopSeq();return()=>wClearTimeout(seqTimer.current);},[connected]);
  const onSend=async(v,opts={})=>{
    if(!connected||!io.current.open)return;
    const crlf=opts.crlf!==false;
    try{
      let bytes;
      if(opts.hex){bytes=new Uint8Array(v.trim().split(/[\s,]+/).map(h=>parseInt(h,16)));
        const norm=[...bytes].map(b=>b.toString(16).toUpperCase().padStart(2,'0')).join(' ');
        await io.current.writer.write(bytes);
        push('tx',norm,norm);
      }else{bytes=new TextEncoder().encode(v+(crlf?'\r\n':''));
        if(prefs.enc==='gbk'&&/[^\x00-\x7F]/.test(v))push('sys',t('提示：发送方向暂不支持 GBK 编码，非 ASCII 字符已按 UTF-8 发出','note: TX encoding is UTF-8 only; non-ASCII sent as UTF-8'));
        await io.current.writer.write(bytes);
        push('tx',v);
      }
      setStats(s=>({...s,tx:s.tx+bytes.length}));
    }catch(e){push('err',t('发送失败','write failed')+': '+e.message);}
  };
  const entry=ports.find(p=>p.id===cfg.port);
  const portName=entry?entry.label:t('未选串口','no port');
  const frame=`${cfg.dataBits}-${cfg.parity}-${cfg.stopBits}`;
  const prevConn=React.useRef(connected);
  React.useEffect(()=>{
    if(prevConn.current===connected)return;
    prevConn.current=connected;
    if(connected)push('sys',`${t('端口已打开','port opened')} — ${portName} · ${bd(cfg)} · ${frame}${cfg.flow&&cfg.flow!=='none'?' · '+cfg.flow:''}${cfg.autoRe?' · '+t('自动重连','auto-reconnect'):''}`);
    else push('sys',t('端口已关闭','port closed'));
  },[connected]);
  const onConnect=()=>setConnected(!connected);
  const onPulse=async k=>{
    if(!connected)return;
    if(entry&&io.current.open){
      try{
        await io.current.port.setSignals(k==='DTR'?{dataTerminalReady:false}:{requestToSend:false});
        await new Promise(r=>setTimeout(r,100));
        await io.current.port.setSignals(k==='DTR'?{dataTerminalReady:true}:{requestToSend:true});
      }catch(e){push('err','pulse: '+e.message);return;}
    }
    push('sys',`${k} ${t('脉冲','pulse')} 100 ms ↓↑`);
  };
  const fmtDate=ms=>{const d=new Date(ms),p=(n,l=2)=>String(n).padStart(l,'0');return `${d.getFullYear()}-${p(d.getMonth()+1)}-${p(d.getDate())} `;};
  const guardStart=async()=>{
    guardBuf.current={buf:[],ring:[],pending:0};
    let wake='na';
    try{if(navigator.wakeLock){wakeRef.current=await navigator.wakeLock.request('screen');wake='ok';}}catch(e){wake='fail';}
    guardFile.current=null;
    if(window.showSaveFilePicker){
      try{
        const g=guardRef.current;
        const d=new Date(),p=n=>String(n).padStart(2,'0');
        const h=await window.showSaveFilePicker({suggestedName:`serial-guard_${d.getFullYear()}${p(d.getMonth()+1)}${p(d.getDate())}_${p(d.getHours())}${p(d.getMinutes())}.${g.fmt==='csv'?'csv':'txt'}`});
        const w=await h.createWritable();
        guardFile.current={w,q:Promise.resolve()};
        if(g.fmt==='csv')guardFile.current.q=guardFile.current.q.then(()=>w.write('\ufeffdatetime,dir,hit,data\n'));
        push('sys',t('直写磁盘已开启 — 边收边存，崩溃不丢数据','streaming to disk — crash-safe'));
      }catch(e){guardFile.current=null;push('sys',t('未选择磁盘文件，改为停止时下载','no disk file — will download on stop'));}
    }
    setGuard(s=>({...s,on:true,hits:0,buffered:0,startAt:Date.now(),tick:0,wake}));
    push('sys',`${t('无人值守开始','unattended capture started')} · kw="${guardRef.current.kw}" · ${wake==='ok'?t('防待机已开启','wake lock on'):t('防待机不可用','wake lock unavailable')}`);
  };
  const guardStop=()=>{
    try{wakeRef.current&&wakeRef.current.release();}catch(e){}
    wakeRef.current=null;
    const g=guardRef.current,gb=guardBuf.current,rows=gb.buf;
    if(guardFile.current){
      const gw=guardFile.current;guardFile.current=null;
      gw.q.then(()=>gw.w.close()).catch(()=>{});
      push('sys',`${t('无人值守结束，文件已保存','capture stopped, file saved')} · ${t('命中','hits')} ${g.hits}`);
      setGuard(s=>({...s,on:false,startAt:null}));
      return;
    }
    const hitMark=r=>g.kw&&r.kind==='rx'&&r.ascii.includes(g.kw);
    let content,ext,mime;
    if(g.fmt==='csv'){content='datetime,dir,hit,data\n'+rows.filter(r=>!r.sep).map(r=>`${fmtDate(r.tsms)+r.time},${r.kind},${hitMark(r)?1:0},"${r.ascii.replace(/"/g,'""')}"`).join('\n');ext='csv';mime='text/csv';}
    else{content=rows.map(r=>r.sep?`\n===== ${t('命中','HIT')} @ ${fmtDate(r.at)+new Date(r.at).toTimeString().slice(0,8)} =====`:`[${fmtDate(r.tsms)+r.time}] [${r.kind.toUpperCase()}]${hitMark(r)?' [HIT]':''} ${r.ascii}`).join('\n');ext='txt';mime='text/plain';}
    if(rows.length){
      const d=new Date(),p=n=>String(n).padStart(2,'0');
      const a=document.createElement('a');
      a.href=URL.createObjectURL(new Blob(['\ufeff'+content],{type:mime+';charset=utf-8'}));
      a.download=`serial-guard_${d.getFullYear()}${p(d.getMonth()+1)}${p(d.getDate())}_${p(d.getHours())}${p(d.getMinutes())}${p(d.getSeconds())}.${ext}`;
      a.click();URL.revokeObjectURL(a.href);
    }
    push('sys',`${t('无人值守结束','unattended capture stopped')} · ${t('命中','hits')} ${g.hits} · ${t('导出','exported')} ${rows.filter(r=>!r.sep).length} ${t('行','lines')}`);
    setGuard(s=>({...s,on:false,startAt:null}));
  };
  React.useEffect(()=>{
    const h=async()=>{if(document.visibilityState==='visible'&&guardRef.current.on&&navigator.wakeLock&&!wakeRef.current){try{wakeRef.current=await navigator.wakeLock.request('screen');}catch(e){}}};
    document.addEventListener('visibilitychange',h);
    return()=>{document.removeEventListener('visibilitychange',h);try{wakeRef.current&&wakeRef.current.release();}catch(e){}};
  },[]);
  React.useEffect(()=>{
    if(!guard.on)return;
    const iv=setInterval(()=>setGuard(s=>s.on?{...s,tick:s.tick+1}:s),1000);
    return()=>clearInterval(iv);
  },[guard.on]);
  const onSendFile=async f=>{
    if(!connected||fileProg!=null)return;
    push('sys',`${t('开始发送文件','sending file')} ${f.name} (${f.size.toLocaleString()} B)`);
    if(io.current.open){
      try{
        const buf=new Uint8Array(await f.arrayBuffer());
        const CHUNK=1024;let sent=0;
        while(sent<buf.length&&io.current.open){
          await io.current.writer.write(buf.slice(sent,sent+CHUNK));
          sent+=Math.min(CHUNK,buf.length-sent);
          setStats(s=>({...s,tx:s.tx+Math.min(CHUNK,buf.length)}));
          setFileProg(sent/buf.length);
        }
        setFileProg(null);push('sys',`${t('文件发送完成','file sent')} ${f.name}`);
      }catch(e){setFileProg(null);push('err',t('文件发送失败','file send failed')+': '+e.message);}
      return;
    }
    setFileProg(null);
  };
  React.useEffect(()=>{
    if(!connected||!auto.on)return;
    const ms=Math.max(50,+auto.ms||1000);
    const iv=wSetInterval(()=>{if((auto.text||'').trim())onSend(auto.text);},ms);
    return()=>wClearInterval(iv);
  },[connected,auto]);
  React.useEffect(()=>{if(!connected&&auto.on)setAuto(a=>({...a,on:false}));},[connected]);
  const onSelect=ls=>setInspected(ls);
  const onDecode=()=>setDecode(true);
  const onExpand=()=>{
    setInspected(cur=>{
      if(!cur.length)return cur;
      const idxs=cur.map(l=>lines.indexOf(l)).filter(i=>i>=0);
      if(!idxs.length)return cur;
      let a=Math.min(...idxs),b=Math.max(...idxs);
      const kind=lines[a].kind;
      const hexish=l=>/^([0-9A-Fa-f]{2}[\s]+){2,}/.test(l.ascii.replace(/\\r|\\n/g,'').trim()+' ');
      while(a>0&&lines[a-1].kind===kind&&hexish(lines[a-1]))a--;
      while(b<lines.length-1&&lines[b+1].kind===kind&&hexish(lines[b+1]))b++;
      return lines.slice(a,b+1);
    });
  };
  const drag=(e,which)=>{
    const startX=e.clientX,w0=which==='sb'?sbW:chW;
    const mm=ev=>{const d=ev.clientX-startX;if(which==='sb')setSbW(Math.max(210,Math.min(420,w0+d)));else setChW(Math.max(250,Math.min(560,w0-d)));};
    const up=()=>{window.removeEventListener('mousemove',mm);window.removeEventListener('mouseup',up);document.body.style.cursor='';};
    window.addEventListener('mousemove',mm);window.addEventListener('mouseup',up);
    document.body.style.cursor='col-resize';e.preventDefault();
  };
  const paneRef=React.useRef(null);
  const [paneW,setPaneW]=React.useState(9999);
  React.useEffect(()=>{
    const el=paneRef.current;if(!el||typeof ResizeObserver==='undefined')return;
    const ro=new ResizeObserver(es=>{for(const e of es)setPaneW(e.contentRect.width);});
    ro.observe(el);return()=>ro.disconnect();
  },[mode]);
  const chEff=Math.min(chW,paneW-360);
  const showChart=plot&&chEff>=220;
  const [hostEl,setHostEl]=React.useState(null);
  React.useEffect(()=>{setHostEl(document.getElementById('om-sidebar-host'));},[]);
  const selfLabel=`${portName} · ${bd(cfg)}`;
  const sidebarPortal=isTarget&&hostEl?ReactDOM.createPortal(
    <SidebarConfig connected={connected} onConnect={onConnect} cfg={cfg} setCfg={setCfg} auto={auto} setAuto={setAuto} txVal={txVal} onSend={onSend} onPulse={onPulse} reply={reply} setReply={setReply} trig={trig} setTrig={setTrig} ports={ports} setPorts={setPorts} requestPort={requestPort} otherUsed={otherUsed} guard={guard} setGuard={setGuard} guardStart={guardStart} guardStop={guardStop} sidebar={sidebarMeta} sigs={sigs} onSignals={onSignals} paneTag={paneTag} selfLabel={selfLabel} seqCfg={seqCfg} setSeqCfg={setSeqCfg} seqRun={seqRun} runSeq={runSeq} stopSeq={stopSeq} dtr={dtr} rts={rts} setDtrS={setDtrS} setRtsS={setRtsS}/>,hostEl):null;
  const handleStyle={width:5,flex:'none',cursor:'col-resize',borderRadius:2,background:'transparent',transition:'background .15s'};
  const miniRef=React.useRef(null);
  const [miniVal,setMiniVal]=React.useState('');
  const [miniEsc,setMiniEsc]=React.useState(true);
  const [miniHex,setMiniHex]=React.useState(false);
  const [miniCrlf,setMiniCrlf]=React.useState(true);
  const miniFrozen=React.useRef(null);
  const miniSend=()=>{if(miniVal.trim()&&connected){onSend(miniVal,{hex:miniHex,crlf:miniCrlf});setMiniVal('');}};
  React.useEffect(()=>{const el=miniRef.current;if(el&&mode==='mini'&&!paused)requestAnimationFrame(()=>{el.scrollTop=el.scrollHeight;});},[lines,mode,paused]);
  if(mode==='mini'){
    const hit=trig.on&&trig.kw&&lines.slice(-5).some(l=>l.kind==='rx'&&l.ascii.includes(trig.kw));
    if(paused){if(!miniFrozen.current)miniFrozen.current=lines;}else miniFrozen.current=null;
    const wrapMini=inner=><React.Fragment>{sidebarPortal}{inner}</React.Fragment>;
    const mview=paused&&miniFrozen.current?miniFrozen.current:lines;
    const mshow=l=>hex?l.hex:(miniEsc?l.ascii:l.ascii.replace(/\\r|\\n/g,''));
    const mb=(on,lbl,fn,ttl)=><Badge tone={on?'accent':'neutral'} style={{cursor:'pointer',textTransform:'none'}} onClick={e=>{e.stopPropagation();fn();}} title={ttl||''}>{lbl}</Badge>;
    return wrapMini(<div onClick={onTarget} style={{...pos,minHeight:0,minWidth:0,display:'flex',flexDirection:'column',background:'var(--bg-1)',border:`1px solid ${isTarget?'var(--accent-0)':hit?'var(--err-0)':'var(--border-1)'}`,borderRadius:'var(--radius-1)',boxShadow:isTarget?'0 0 10px rgba(0,229,255,.3)':hit?'0 0 10px rgba(255,92,92,.35)':'none',overflow:'hidden',cursor:'pointer'}}>
      <div style={{flex:'none',display:'flex',alignItems:'center',gap:8,padding:'4px 8px',borderBottom:'1px solid var(--border-0)'}}>
        <StatusLight tone={connected?'ok':'off'} pulse={connected}/>
        {paneTag&&<Badge tone="neutral">{paneTag}</Badge>}
        <span style={{fontSize:11,fontWeight:600,color:isTarget?'var(--accent-0)':'var(--fg-1)',fontFamily:'var(--font-mono)',whiteSpace:'nowrap',overflow:'hidden',textOverflow:'ellipsis'}}>{portName} · {bd(cfg)}</span>
        {isTarget&&<Badge tone="accent">{t('配置中','CONFIG')}</Badge>}
        {hit&&<Badge tone="err">{t('触发','TRIG')}</Badge>}
        <div style={{flex:1}}></div>
        <span style={{fontSize:10,color:'var(--accent-0)',fontFamily:'var(--font-mono)'}}>{lines.filter(l=>l.kind==='rx').length.toLocaleString()} {t('行','ln')}</span>
        <span style={{fontSize:9,color:'var(--fg-3)',fontFamily:'var(--font-mono)'}}>RX {stats.rx.toLocaleString()} B</span>
        <IconButton title={t('切换为主监控','make primary')} onClick={e=>{e.stopPropagation();onSwap();}}>⇄</IconButton>
      </div>
      <div style={{flex:'none',display:'flex',alignItems:'center',gap:4,padding:'3px 8px',borderBottom:'1px solid var(--border-0)'}} onClick={e=>e.stopPropagation()}>
        {mb(hex,'HEX',()=>setHex(true))}
        {mb(!hex,'ASCII',()=>setHex(false))}
        {mb(miniEsc,'\\r\\n',()=>setMiniEsc(!miniEsc),t('显示/隐藏转义符','show/hide escapes'))}
        <div style={{flex:1}}></div>
        <IconButton title={paused?t('继续','resume'):t('暂停','pause')} active={paused} onClick={()=>setPaused(!paused)}>{paused?'⏵':'⏸'}</IconButton>
        <Button size="sm" variant="ghost" onClick={()=>{setLines([]);setStats({tx:0,rx:0});setSamples([]);}}>{t('清空','CLEAN')}</Button>
      </div>
      <div ref={miniRef} style={{flex:1,overflowY:'auto',minHeight:0,padding:'2px 4px',background:'var(--surface-terminal,var(--bg-0))'}}>
        {mview.length===0&&<div style={{color:'var(--fg-3)',fontSize:11,padding:4}}>{t('// 未连接','// not connected')}</div>}
        {paused&&<div style={{color:'var(--warn-0,#ffb454)',fontSize:10,padding:'2px 4px'}}>⏸ {t('已暂停显示','display paused')} (+{(lines.length-mview.length).toLocaleString()})</div>}
        {mview.slice(-80).map((l,i)=><TermLine key={i} kind={l.kind} time={l.time}>{mshow(l)}</TermLine>)}
      </div>
      <div style={{flex:'none',display:'flex',alignItems:'center',gap:4,padding:'4px 6px',borderTop:'1px solid var(--border-0)'}} onClick={e=>e.stopPropagation()}>
        {mb(miniHex,'HEX',()=>setMiniHex(true),t('十六进制发送','send as hex'))}
        {mb(!miniHex,'ASCII',()=>setMiniHex(false),t('文本发送','send as text'))}
        {mb(miniCrlf,'+\\r\\n',()=>setMiniCrlf(!miniCrlf),t('发送后追加 \\r\\n','append CRLF'))}
        <input value={miniVal} onChange={e=>setMiniVal(e.target.value)} onKeyDown={e=>e.key==='Enter'&&miniSend()} placeholder={connected?(miniHex?'AA 55 0F…':t('发送…','send…')):t('// 未连接','// not connected')} disabled={!connected} style={{flex:1,minWidth:0,height:22,background:'var(--surface-input)',border:'1px solid var(--border-1)',borderRadius:'var(--radius-1)',color:'var(--fg-0)',fontFamily:'var(--font-mono)',fontSize:11,padding:'0 6px',outline:'none'}}/>
        <Button size="sm" variant="primary" disabled={!connected||!miniVal.trim()} onClick={miniSend}>{t('发','TX')}</Button>
      </div>
    </div>);
  }
  return <div style={{...pos,minHeight:0,minWidth:0,display:mode==='hidden'?'none':'flex',flexDirection:'column'}}>
    {sidebarPortal}
    <main ref={paneRef} style={{flex:1,minHeight:0,display:'flex',gap:3,padding:8}}>
      <div style={{flex:1,minWidth:0,display:'flex',flexDirection:'column',gap:8}}>
        <div style={{flex:1,minHeight:0,display:'flex',gap:3}}>
          <MonitorPanel lines={lines} hex={hex} setHex={setHex} paused={paused} setPaused={setPaused} onSend={onSend} onClear={()=>{setLines([]);setInspected([]);setStats({tx:0,rx:0});setSamples([]);}} connected={connected} onSelect={onSelect} onDecode={onDecode} inspected={inspected} val={txVal} setVal={setTxVal} onSendFile={onSendFile} fileProg={fileProg} hotkeys={visible} trig={trig}/>
          {showChart&&<><div style={handleStyle} onMouseDown={e=>drag(e,'ch')} onMouseEnter={e=>e.target.style.background='var(--accent-dim)'} onMouseLeave={e=>e.target.style.background='transparent'}></div>
          <PlotPanel running={connected} samples={samples} channels={channels} chartFmt={chartFmt} setChartFmt={setChartFmt} stats={stats} rxLines={lines.filter(l=>l.kind==='rx').length} monPaused={paused} width={chEff} onClose={()=>setPlot(false)}/></>}
        </div>
        {decode&&<ProtocolPanel sel={inspected} onExpand={onExpand} onClose={()=>{setDecode(false);setInspected([]);}}/>}
      </div>
    </main>
    <footer style={{height:24,flex:'none',display:'flex',alignItems:'center',gap:12,padding:'0 12px',borderTop:'1px solid var(--border-1)',background:'var(--bg-1)',fontSize:10,color:'var(--fg-3)',whiteSpace:'nowrap',overflow:'hidden'}}>
      <span style={{color:'var(--tx-0)',flex:'none'}}>TX {stats.tx.toLocaleString()} B</span>
      <span style={{color:'var(--rx-0)',flex:'none'}}>RX {stats.rx.toLocaleString()} B</span>
      {paneW>560&&<span style={{flex:'none'}}>{t('缓冲','BUFFER')} {lines.length.toLocaleString()} / {BUF_MAX.toLocaleString()}</span>}
      <div style={{flex:1,minWidth:0}}></div>
      <Button variant="ghost" size="sm" onClick={()=>setPlot(!plot)}>{t('图表','CHART')}</Button>
      <Button variant="ghost" size="sm" onClick={()=>setDecode(!decode)}>{t('解析','DECODE')}</Button>
      {paneW>640&&<span style={{flex:'none'}}>{SER?'Web Serial API':t('浏览器不支持 Web Serial','Web Serial unsupported')}</span>}
    </footer>
  </div>;
}
const LAYOUT_KEY='mrocioa.layout';
const LAYOUT_DEFAULT_MARKER='mrocioa.layout-default-1+3-v1';
function loadDefaultLayout(){
  try{
    if(localStorage.getItem(LAYOUT_DEFAULT_MARKER)!=='1'){
      localStorage.setItem(LAYOUT_DEFAULT_MARKER,'1');
      localStorage.setItem(LAYOUT_KEY,'4');
      return '4';
    }
    const saved=localStorage.getItem(LAYOUT_KEY);
    return ['1','2','4'].includes(saved)?saved:'4';
  }catch(e){return '4';}
}
function MainScreen(){
  const [ports,setPorts]=React.useState([]);
  React.useEffect(()=>{
    if(!SER)return;
    (async()=>{try{const ps=await SER.getPorts();if(ps.length)setPorts(cur=>[...cur,...ps.filter(p=>!cur.some(e=>e.port===p)).map(mkEntry)]);}catch(e){}})();
    const onCon=e=>setPorts(cur=>cur.some(x=>x.port===e.target)?cur.map(x=>x.port===e.target?{...x,off:false}:x):[...cur,mkEntry(e.target)]);
    const onDis=e=>setPorts(cur=>cur.map(x=>x.port===e.target?{...x,off:true}:x));
    SER.addEventListener('connect',onCon);SER.addEventListener('disconnect',onDis);
    return()=>{SER.removeEventListener('connect',onCon);SER.removeEventListener('disconnect',onDis);};
  },[]);
  const requestPort=async()=>{
    if(!SER)return {err:'浏览器不支持 Web Serial (需 Chrome/Edge 89+ · HTTPS)'};
    try{
      const p=await SER.requestPort();
      let added=null;
      setPorts(cur=>{if(cur.some(e=>e.port===p))return cur;added=mkEntry(p);return [...cur,added];});
      return {ok:true};
    }catch(e){return {err:e.message};}
  };
  const [sessions,setSessions]=React.useState([{id:1,cfg:{port:'',baud:'115200',baudCustom:'250000',dataBits:'8',parity:'N',stopBits:'1',flow:'none',framing:'crlf',autoRe:false},connected:false}]);
  React.useEffect(()=>{
    if(!ports.length)return;
    setSessions(ss=>{
      const used=[];
      return ss.map(s=>{
        if(s.cfg.port&&ports.some(p=>p.id===s.cfg.port)){used.push(s.cfg.port);return s;}
        const free=ports.find(p=>!used.includes(p.id));
        if(!free)return s;
        used.push(free.id);
        return {...s,cfg:{...s.cfg,port:free.id}};
      });
    });
  },[ports]);
  const [active,setActive]=React.useState(0);
  const [targetId,setTargetId]=React.useState(null);
  React.useEffect(()=>{setTargetId(null);},[active]);
  const [layout,setLayout]=React.useState(loadDefaultLayout);
  const [sbW,setSbW]=React.useState(272);
  const sbDrag=e=>{
    const startX=e.clientX,w0=sbW;
    const mm=ev=>setSbW(Math.max(220,Math.min(420,w0+ev.clientX-startX)));
    const up=()=>{window.removeEventListener('mousemove',mm);window.removeEventListener('mouseup',up);document.body.style.cursor='';};
    window.addEventListener('mousemove',mm);window.addEventListener('mouseup',up);
    document.body.style.cursor='col-resize';e.preventDefault();
  };
  React.useEffect(()=>{try{localStorage.setItem(LAYOUT_KEY,layout);}catch(e){}},[layout]);
  const [lang,setLang]=React.useState(()=>{try{return localStorage.getItem('mrocioa.lang')||'en';}catch(e){return 'en';}});
  const [prefs,setPrefs]=React.useState(()=>{const def={tsFmt:'abs',fontSz:12,enc:'utf8'};try{return {...def,...JSON.parse(localStorage.getItem('mrocioa.prefs')||'{}')};}catch(e){return def;}});
  React.useEffect(()=>{try{localStorage.setItem('mrocioa.lang',lang);localStorage.setItem('mrocioa.prefs',JSON.stringify(prefs));}catch(e){}},[lang,prefs]);
  const [setOpen,setSetOpen]=React.useState(false);
  const [logOpen,setLogOpen]=React.useState(false);
  const [verOpen,setVerOpen]=React.useState(false);
  React.useEffect(()=>{if(!setOpen&&!logOpen)return;const h=()=>{setSetOpen(false);setLogOpen(false);};window.addEventListener('click',h);return()=>window.removeEventListener('click',h);},[setOpen,logOpen]);
  const t=window.tt(lang);
  const nextId=React.useRef(2);
  const upd=(id,patch)=>setSessions(ss=>ss.map(s=>s.id===id?{...s,...patch}:s));
  const MAX_SESS=4;
  const addTab=stay=>{
    if(sessions.length>=MAX_SESS)return {ok:false,max:true};
    const used=sessions.map(s=>s.cfg.port);
    const free=ports.filter(p=>!used.includes(p.id));
    if(!free.length)return {ok:false,total:ports.length};
    setSessions(ss=>[...ss,{id:nextId.current++,cfg:{port:free[0].id,baud:'115200',baudCustom:'250000',dataBits:'8',parity:'N',stopBits:'1',flow:'none',framing:'crlf',autoRe:false},connected:false}]);
    if(stay!==true)setActive(sessions.length);
    return {ok:true};
  };
  const closeTab=i=>{
    if(sessions.length===1)return;
    setSessions(ss=>ss.filter((_,j)=>j!==i));
    setActive(a=>Math.max(0,a>=i?a-1:a));
  };
  const cur=sessions[active];
  const [phErr,setPhErr]=React.useState('');
  const phAdd=()=>{
    const r=addTab(true);
    if(!r.ok){setPhErr(r.max?t('已达窗口上限（4 个）','pane limit (4) reached'):t(`仅检测到 ${r.total} 个串口，已全部占用；请先授权新串口`,`only ${r.total} port(s), all in use — grant a new port first`));setTimeout(()=>setPhErr(''),3500);}
  };
  const labelOf=s=>{const e=ports.find(p=>p.id===s.cfg.port);return `${e?e.label:t('未选串口','no port')} · ${bd(s.cfg)}`;};
  const logCmd=cmd=>{window.dispatchEvent(new CustomEvent('om-log',{detail:cmd}));setLogOpen(false);};
  const setRow={display:'flex',flexDirection:'column',gap:4};
  const setLbl={fontSize:10,color:'var(--fg-3)'};
  const menuStyle={position:'absolute',top:'calc(100% + 6px)',right:0,zIndex:20,background:'var(--surface-card)',border:'1px solid var(--border-accent)',borderRadius:'var(--radius-1)',boxShadow:'0 6px 20px rgba(0,0,0,.55)',minWidth:170,padding:4,display:'flex',flexDirection:'column'};
  const itemStyle={all:'unset',cursor:'pointer',padding:'6px 8px',fontSize:11,fontFamily:'var(--font-mono)',color:'var(--fg-1)',borderRadius:'var(--radius-1)'};
  return <window.LangCtx.Provider value={lang}><window.PrefsCtx.Provider value={prefs}><div style={{height:'100vh',display:'flex',flexDirection:'column',background:'var(--bg-0)'}}>
    <header style={{height:36,flex:'none',display:'flex',alignItems:'center',gap:12,padding:'0 12px',borderBottom:'1px solid var(--border-1)',background:'var(--bg-1)'}}>
      <span style={{fontSize:14,fontWeight:600,color:'var(--fg-0)'}}>mrocioa<span style={{color:'var(--accent-0)'}}>█</span></span>
      <span style={{fontSize:10,color:'var(--fg-3)',letterSpacing:'.08em'}}>WEB SERIAL DEBUGGER</span>
      {!SER&&<Badge tone="err">{t('浏览器不支持 Web Serial — 仅模拟','no Web Serial — demo only')}</Badge>}
      <div style={{flex:1}}></div>
      <StatusLight tone={cur.connected?'ok':'off'} pulse={cur.connected} label={cur.connected?'CONNECTED':'DISCONNECTED'}/>
      <Badge tone={cur.connected?'accent':'neutral'}>{labelOf(cur).split(' ')[0]}</Badge><Badge>{bd(cur.cfg)}</Badge><Badge>{cur.cfg.dataBits}-{cur.cfg.parity}-{cur.cfg.stopBits}</Badge>
      <div style={{display:'flex',gap:2,alignItems:'center',marginRight:4}}>
        {['1','2','4'].map(v=><Button key={v} size="sm" variant={layout===v?'primary':'ghost'} onClick={()=>setLayout(v)} title={v==='1'?t('单窗','single'):v==='2'?t('主从双窗','1+1'):t('主从四窗','1+3')}>{v==='1'?'▣':v==='2'?'▣|':'▣∷'}</Button>)}
      </div>
      <div style={{position:'relative'}} onClick={e=>e.stopPropagation()}>
        <Button variant="ghost" size="sm" onClick={()=>{setLogOpen(o=>!o);setSetOpen(false);}}>{t('日志','LOG')} ▾</Button>
        {logOpen&&<div style={menuStyle}>
          {[['txt',t('导出当前会话','Export session')+' (.txt)'],['csv',t('导出当前会话','Export session')+' (.csv)'],['rec',t('开始/停止连续记录','Start/stop recording')]].map(([cmd,label])=><button key={cmd} onClick={()=>logCmd(cmd)} style={itemStyle} onMouseEnter={e=>e.target.style.background='var(--accent-dim)'} onMouseLeave={e=>e.target.style.background='transparent'}>{label}</button>)}
        </div>}
      </div>
      <div style={{position:'relative'}} onClick={e=>e.stopPropagation()}>
        <Button variant="ghost" size="sm" onClick={()=>{setSetOpen(o=>!o);setLogOpen(false);}}>⚙ {t('设置','SETTINGS')}</Button>
        {setOpen&&<div style={{...menuStyle,width:240,padding:12,gap:10}}>
          <div style={{fontSize:10,color:'var(--fg-3)',letterSpacing:'.1em'}}>{t('系统设置','SYSTEM SETTINGS')}</div>
          <div style={setRow}><span style={setLbl}>{t('界面语言','Language')}</span>
            <Select options={[{value:'bi',label:'中文 + English'},{value:'zh',label:'仅中文'},{value:'en',label:'English only'}]} value={lang} onChange={e=>setLang(e.target.value)}/></div>
          <div style={setRow}><span style={setLbl}>{t('字符编码','Encoding')}</span>
            <Select options={[{value:'utf8',label:'UTF-8'},{value:'gbk',label:'GBK / GB2312'},{value:'latin1',label:'Latin-1'}]} value={prefs.enc} onChange={e=>setPrefs(p=>({...p,enc:e.target.value}))}/></div>
          <div style={setRow}><span style={setLbl}>{t('终端字号','Terminal font size')}</span>
            <Select options={[{value:11,label:'11 px'},{value:12,label:'12 px'},{value:14,label:'14 px'},{value:16,label:'16 px'}]} value={prefs.fontSz} onChange={e=>setPrefs(p=>({...p,fontSz:+e.target.value}))}/></div>
          <div style={setRow}><span style={setLbl}>{t('时间戳','Timestamps')}</span>
            <Select options={[{value:'abs',label:t('绝对时间 12:04:33.320','absolute 12:04:33.320')},{value:'rel',label:t('相对上一行 +0.702s','relative +0.702s')},{value:'off',label:t('隐藏','hidden')}]} value={prefs.tsFmt} onChange={e=>setPrefs(p=>({...p,tsFmt:e.target.value}))}/></div>
          <div style={{display:'flex',justifyContent:'space-between',fontSize:10,color:'var(--fg-3)'}}><span>{t('接收缓冲上限','RX buffer limit')}</span><span style={{color:'var(--fg-1)',fontFamily:'var(--font-mono)'}}>100,000 {t('行','lines')}</span></div>
          <div style={{borderTop:'1px solid var(--border-0)',paddingTop:8}}>
            <div onClick={()=>setVerOpen(o=>!o)} style={{display:'flex',justifyContent:'space-between',alignItems:'center',cursor:'pointer'}}>
              <span style={{fontSize:10,color:'var(--fg-2)'}}>{t('版本说明','CHANGELOG')}</span>
              <span style={{fontSize:10,color:'var(--fg-3)'}}>Release 1.0.1 {verOpen?'▴':'▾'}</span>
            </div>
            {verOpen&&<div style={{marginTop:6,display:'flex',flexDirection:'column',gap:4,maxHeight:180,overflowY:'auto',fontSize:9,color:'var(--fg-3)',fontFamily:'var(--font-mono)',lineHeight:1.5}}>
              <div>{t('Release 1.0.1 · 正式发行','Release 1.0.1 · Stable release')}</div>
              <div>{t('• 真正全屏：隐藏站点与浏览器导航界面，支持 Esc 退出','• True fullscreen hides site and browser navigation UI, with Esc to exit')}</div>
              <div>{t('• 新增 IR、CEC、I²C、SPI、UART、CAN、LIN 等串口调试日志解析','• Added serial debug-log decoding for IR, CEC, I²C, SPI, UART, CAN, LIN and more')}</div>
              <div>{t('• 首次打开默认采用 1+3 四窗布局，手动选择仍会保存','• New users see the 1+3 four-pane layout by default; manual choices remain saved')}</div>
              <div>{t('• Chrome/Edge 桌面兼容性与本机数据处理保持不变','• Desktop Chrome/Edge compatibility and local-only data processing are unchanged')}</div>
              <div>{t('Release 1.0.0 · 首次正式发行','Release 1.0.0 · Initial stable release')}</div>
              <div>{t('• Web Serial、多会话、收发、监视、导出、图表与基础协议解析','• Web Serial, multi-session I/O, monitoring, export, charts and core protocol decoding')}</div>
            </div>}
          </div>
          <div style={{fontSize:9,color:'var(--fg-3)',borderTop:'1px solid var(--border-0)',paddingTop:8}}>mrocioa web serial debugger · Release 1.0.1 · {t('快捷键','hotkeys')}: Ctrl+L {t('清空','clean')} · Ctrl+F {t('搜索','search')}</div>
        </div>}
      </div>
    </header>
    <div style={{height:6,flex:'none',background:'var(--bg-1)',borderBottom:'1px solid var(--border-1)'}}></div>
    {(()=>{
      const nMini=layout==='1'?0:layout==='2'?1:3;
      const miniIdxs=sessions.map((_,i)=>i).filter(i=>i!==active).slice(0,nMini);
      const gridStyle=layout==='1'?{display:'flex',flexDirection:'column'}:{display:'grid',gridTemplateColumns:'minmax(0,2.2fr) minmax(0,1fr)',gridTemplateRows:layout==='4'?'repeat(3,minmax(0,1fr))':'minmax(0,1fr)',gap:8,padding:8};
      const posOf=i=>{
        if(i===active)return layout==='1'?{flex:1}:{gridColumn:'1',gridRow:layout==='4'?'1 / span 3':'1'};
        const k=miniIdxs.indexOf(i);
        return {gridColumn:'2',gridRow:String(k+1)};
      };
      const modeOf=i=>i===active?'full':miniIdxs.includes(i)?'mini':'hidden';
      const tagOf=i=>i===active?'MAIN':miniIdxs.includes(i)?'SUB'+(miniIdxs.indexOf(i)+1):'BG';
      const tgtSess=sessions.find(s=>s.id===targetId)||cur;
      const sidebarMeta={
        panes:sessions.map((s,i)=>({id:s.id,label:labelOf(s),connected:s.connected,primary:i===active,sub:miniIdxs.includes(i),tag:tagOf(i)})),
        targetId:tgtSess.id,
        pick:id=>setTargetId(id===cur.id?null:id),
        onAdd:()=>addTab(true),
        canAdd:sessions.length<MAX_SESS,
        makePrimary:id=>{const i=sessions.findIndex(x=>x.id===id);if(i>=0)setActive(i);},
        close:id=>{const i=sessions.findIndex(x=>x.id===id);if(i>=0)closeTab(i);}
      };
      return <div style={{flex:1,minHeight:0,display:'flex'}}>
        <aside id="om-sidebar-host" style={{width:sbW,flex:'none',display:'flex',minHeight:0,padding:8,overflow:'hidden'}}></aside>
        <div style={{width:5,flex:'none',cursor:'col-resize',borderRadius:2}} onMouseDown={sbDrag} onMouseEnter={e=>e.target.style.background='var(--accent-dim)'} onMouseLeave={e=>e.target.style.background='transparent'}></div>
        <div style={{flex:1,minWidth:0,minHeight:0,...gridStyle}}>
        {sessions.map((s,i)=><Session key={s.id} paneTag={tagOf(i)} visible={i===active} mode={modeOf(i)} pos={posOf(i)} onSwap={()=>setActive(i)} onTarget={()=>setTargetId(s.id)} isTarget={tgtSess.id===s.id} sidebarMeta={sidebarMeta} cfg={s.cfg} setCfg={f=>upd(s.id,{cfg:typeof f==='function'?f(s.cfg):f})} connected={s.connected} setConnected={v=>upd(s.id,{connected:v})} ports={ports} setPorts={setPorts} requestPort={requestPort} otherUsed={sessions.filter(x=>x.id!==s.id&&x.connected).map(x=>x.cfg.port)}/>)}
        {Array.from({length:Math.max(0,nMini-miniIdxs.length)}).map((_,k)=><div key={'ph'+k} onClick={phAdd} style={{gridColumn:'2',gridRow:String(miniIdxs.length+k+1),border:`1px dashed ${phErr?'var(--err-0)':'var(--border-1)'}`,borderRadius:'var(--radius-1)',display:'flex',alignItems:'center',justifyContent:'center',color:phErr?'var(--err-0)':'var(--fg-3)',fontSize:11,cursor:'pointer',padding:8,textAlign:'center'}}>{phErr||('+ '+t('新建会话','new session'))}</div>)}
        </div>
      </div>;
    })()}
  </div></window.PrefsCtx.Provider></window.LangCtx.Provider>;
}
window.MainScreen=MainScreen;
