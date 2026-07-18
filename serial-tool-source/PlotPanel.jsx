const {Card,Badge,IconButton,Select} = window.MrocioaDesignSystem_b37fe9;
window.LangCtx=window.LangCtx||React.createContext('bi');
window.tt=window.tt||(l=>(zh,en)=>l==='zh'?zh:l==='en'?en:zh+' '+en);
const PAL=['#00e5ff','#00ff9c','#ffb454','#ff5c8a','#c792ea','#82aaff'];
function drawChart(cv,samples,paused,chans,win){
  const ctx=cv.getContext('2d');
  const W=cv.width=cv.clientWidth*2,H=cv.height=cv.clientHeight*2;
  ctx.clearRect(0,0,W,H);
  const padL=64,padR=14,padT=14,padB=10,cw=W-padL-padR,ch=H-padT-padB;
  ctx.strokeStyle='rgba(0,229,255,.07)';ctx.lineWidth=1;
  for(let i=0;i<=4;i++){const y=padT+ch*i/4;ctx.beginPath();ctx.moveTo(padL,y);ctx.lineTo(W-padR,y);ctx.stroke();}
  for(let i=0;i<=6;i++){const x=padL+cw*i/6;ctx.beginPath();ctx.moveTo(x,padT);ctx.lineTo(x,H-padB);ctx.stroke();}
  if(!samples.length)return;
  chans.forEach((c,ci)=>{
    const vals=samples.map(s=>s[c.key]).filter(v=>v!=null);
    if(!vals.length)return;
    let lo=Math.min(...vals),hi=Math.max(...vals);
    if(hi-lo<1e-9){const m=(hi+lo)/2;lo=m-1;hi=m+1;}
    const pad=(hi-lo)*.15;lo-=pad;hi+=pad;
    const pts=[];samples.forEach((s,i)=>{if(s[c.key]!=null)pts.push([i,s[c.key]]);});
    const X=i=>padL+cw*(i+win-samples.length)/(win-1),Y=v=>padT+ch*(1-(v-lo)/(hi-lo));
    ctx.beginPath();
    pts.forEach(([i,v],k)=>k?ctx.lineTo(X(i),Y(v)):ctx.moveTo(X(i),Y(v)));
    ctx.lineTo(X(pts[pts.length-1][0]),H-padB);ctx.lineTo(X(pts[0][0]),H-padB);ctx.closePath();
    ctx.fillStyle=c.color+'14';ctx.fill();
    ctx.strokeStyle=c.color;ctx.lineWidth=2.5;ctx.shadowColor=c.color;ctx.shadowBlur=8;ctx.beginPath();
    pts.forEach(([i,v],k)=>k?ctx.lineTo(X(i),Y(v)):ctx.moveTo(X(i),Y(v)));
    ctx.stroke();ctx.shadowBlur=0;
    const [li,lv]=pts[pts.length-1];
    ctx.fillStyle=c.color;ctx.beginPath();ctx.arc(X(li),Y(lv),5,0,7);ctx.fill();
    ctx.font='500 18px IBM Plex Mono,monospace';ctx.textAlign='right';
    ctx.fillStyle=c.color;ctx.globalAlpha=.9;
    ctx.fillText(hi.toFixed(1),padL-8,padT+16+ci*22);
    ctx.fillText(lo.toFixed(1),padL-8,H-padB-4-ci*22);
    ctx.globalAlpha=1;
  });
  if(paused){ctx.fillStyle='rgba(0,0,0,.45)';ctx.fillRect(0,0,W,H);}
}
function drawRate(cv,hist){
  const ctx=cv.getContext('2d');
  const W=cv.width=cv.clientWidth*2,H=cv.height=cv.clientHeight*2;
  ctx.clearRect(0,0,W,H);
  const max=Math.max(10,...hist.map(p=>Math.max(p.tx,p.rx)));
  [['tx','#ffb454'],['rx','#00ff9c']].forEach(([k,c])=>{
    ctx.strokeStyle=c;ctx.lineWidth=2;ctx.beginPath();
    hist.forEach((p,i)=>{const x=i/59*W,y=H-2-(p[k]/max)*(H-6);i?ctx.lineTo(x,y):ctx.moveTo(x,y);});
    ctx.stroke();
  });
}
function PlotPanel({running,samples,channels,chartFmt,setChartFmt,stats,rxLines,monPaused,width,onClose}){
  const t=window.tt(React.useContext(window.LangCtx));
  const [win,setWin]=React.useState(120);
  const [enabled,setEnabled]=React.useState({});
  React.useEffect(()=>{
    setEnabled(en=>{
      const nu={...en};let changed=false;
      channels.forEach((k,i)=>{if(!(k in nu)){nu[k]=Object.values(nu).filter(Boolean).length<2;changed=true;}});
      return changed?nu:en;
    });
  },[channels]);
  const chans=channels.map((k,i)=>({key:k,color:PAL[i%PAL.length]})).filter(c=>enabled[c.key]);
  const frozenRx=React.useRef(null);
  if(monPaused){if(frozenRx.current==null)frozenRx.current=rxLines;}else frozenRx.current=null;
  const shownRx=monPaused&&frozenRx.current!=null?frozenRx.current:rxLines;
  const cntRef=React.useRef(null);
  React.useEffect(()=>{const el=cntRef.current;if(!el||monPaused)return;el.style.transform='scale(1.3)';el.style.textShadow='0 0 14px var(--accent-0)';const id=setTimeout(()=>{el.style.transform='scale(1)';el.style.textShadow='0 0 6px transparent';},140);return()=>clearTimeout(id);},[shownRx,monPaused]);
  const ref=React.useRef(null),rateRef=React.useRef(null),wrapRef=React.useRef(null);
  const [paused,setPaused]=React.useState(false);
  const frozen=React.useRef(null);
  const [cursor,setCursor]=React.useState(null);
  const [rateHist,setRateHist]=React.useState([]);
  const prev=React.useRef({tx:0,rx:0,at:performance.now()});
  const statsRef=React.useRef(stats);statsRef.current=stats;
  React.useEffect(()=>{
    const iv=setInterval(()=>{
      const nowT=performance.now(),dt=(nowT-prev.current.at)/1000;
      setRateHist(h=>{
        const p=prev.current;
        const pt={tx:Math.max(0,(statsRef.current.tx-p.tx)/dt),rx:Math.max(0,(statsRef.current.rx-p.rx)/dt)};
        prev.current={...statsRef.current,at:nowT};
        return [...h.slice(-59),pt];
      });
    },500);
    return()=>clearInterval(iv);
  },[]);
  const viewSamples=(paused&&frozen.current?frozen.current:samples).slice(-win);
  React.useEffect(()=>{
    if(paused){if(!frozen.current)frozen.current=samples;}
    else frozen.current=null;
    const cv=ref.current;if(cv)drawChart(cv,viewSamples,paused,chans,win);
  },[samples,paused,enabled,channels,win]);
  React.useEffect(()=>{const cv=rateRef.current;if(cv)drawRate(cv,rateHist);},[rateHist]);
  const last=viewSamples[viewSamples.length-1];
  const rate=rateHist[rateHist.length-1]||{tx:0,rx:0};
  const exportCsv=()=>{
    const keys=channels;
    const content=['idx,'+keys.join(',')].concat(samples.map((s,i)=>i+','+keys.map(k=>s[k]??'').join(','))).join('\n');
    const a=document.createElement('a');a.href=URL.createObjectURL(new Blob(['\ufeff'+content],{type:'text/csv;charset=utf-8'}));a.download='chart-data.csv';a.click();URL.revokeObjectURL(a.href);
  };
  const exportPng=()=>{const cv=ref.current;if(!cv)return;const a=document.createElement('a');a.href=cv.toDataURL('image/png');a.download='chart.png';a.click();};
  const onMove=e=>{
    const el=ref.current;if(!el||!viewSamples.length)return;
    const r=el.getBoundingClientRect();
    const padL=32,padR=7;
    const frac=(e.clientX-r.left-padL)/(r.width-padL-padR);
    const idx=Math.round(frac*(win-1))-(win-viewSamples.length);
    if(idx>=0&&idx<viewSamples.length)setCursor({idx,x:e.clientX-r.left});else setCursor(null);
  };
  const curSample=cursor?viewSamples[cursor.idx]:null;
  return <Card title={t('数据图表','CHART')} style={{width:width||340,flex:'none'}}
    actions={<><IconButton title={paused?t('继续','resume'):t('暂停','pause')} active={paused} onClick={()=>setPaused(!paused)}>{paused?'⏵':'⏸'}</IconButton><IconButton onClick={onClose}>×</IconButton></>}
    bodyStyle={{background:'var(--surface-terminal)',padding:0,display:'flex',flexDirection:'column'}}>
    <div style={{display:'flex',alignItems:'center',gap:10,padding:'8px 12px',borderBottom:'1px solid var(--border-0)',flex:'none'}}>
      <span style={{fontSize:9,color:'var(--fg-3)',letterSpacing:'.1em',lineHeight:1.3}}>{t('本次接收','RX LINES')}<br/>{t('行数','THIS SESSION')}</span>
      <span ref={cntRef} style={{fontSize:26,fontWeight:700,color:monPaused?'var(--fg-3)':'var(--accent-0)',fontFamily:'var(--font-mono)',transition:'transform .12s ease-out, text-shadow .12s ease-out',display:'inline-block',transformOrigin:'left center'}}>{(shownRx||0).toLocaleString()}</span>
      {monPaused&&<Badge>{t('已暂停','PAUSED')}</Badge>}
      <div style={{flex:1}}></div>
      <div style={{width:104}}><Select options={[{value:'auto',label:t('自动','Auto')},{value:'json',label:'JSON'},{value:'csv',label:'CSV'},{value:'kv',label:'k=v'}]} value={chartFmt} onChange={e=>setChartFmt(e.target.value)} title={t('数据格式','data format')} style={{height:'var(--ctl-h-sm)',fontSize:'var(--fs-label)'}}/></div>
      <div style={{width:84}}><Select options={[{value:60,label:'60'},{value:120,label:'120'},{value:300,label:'300'}]} value={win} onChange={e=>setWin(+e.target.value)} title={t('采样窗口','window')} style={{height:'var(--ctl-h-sm)',fontSize:'var(--fs-label)'}}/></div>
    </div>
    <div style={{display:'flex',flexWrap:'wrap',gap:6,padding:'6px 12px',borderBottom:'1px solid var(--border-0)',flex:'none'}}>
      {channels.length===0&&<span style={{fontSize:10,color:'var(--fg-3)'}}>{t('// 等待数据 — 从接收流中自动发现数值通道','// waiting for data — channels auto-discovered from RX')}</span>}
      {channels.map((k,i)=>{const color=PAL[i%PAL.length];const on=!!enabled[k];return <div key={k} onClick={()=>setEnabled(en=>({...en,[k]:!en[k]}))} title={t('点击显示/隐藏此通道','toggle channel')} style={{display:'flex',alignItems:'baseline',gap:5,cursor:'pointer',userSelect:'none',opacity:on?1:.35,padding:'1px 6px',border:`1px solid ${on?color+'55':'var(--border-0)'}`,borderRadius:'var(--radius-1)'}}>
        <span style={{width:8,height:8,background:color,borderRadius:1,alignSelf:'center',boxShadow:on?`0 0 6px ${color}`:'none'}}></span>
        <span style={{fontSize:11,color:'var(--fg-2)',fontFamily:'var(--font-mono)'}}>{k}</span>
        <span style={{fontSize:13,fontWeight:600,color,fontFamily:'var(--font-mono)'}}>{last&&on&&last[k]!=null?(+last[k]).toFixed(2).replace(/\.?0+$/,''):'--'}</span>
      </div>;})}
    </div>
    <div ref={wrapRef} style={{position:'relative',flex:1,minHeight:0,display:'flex'}}>
      <canvas ref={ref} onMouseMove={onMove} onMouseLeave={()=>setCursor(null)} style={{width:'100%',flex:1,display:'block',minHeight:0}}></canvas>
      {cursor&&curSample&&<>
        <div style={{position:'absolute',top:0,bottom:0,left:cursor.x,width:1,background:'rgba(0,229,255,.5)',pointerEvents:'none'}}></div>
        <div style={{position:'absolute',top:6,right:8,background:'rgba(6,16,22,.92)',border:'1px solid var(--border-accent)',borderRadius:'var(--radius-1)',padding:'4px 8px',fontSize:10,fontFamily:'var(--font-mono)',pointerEvents:'none',display:'flex',flexDirection:'column',gap:2}}>
          {chans.map(c=><span key={c.key} style={{color:c.color}}>{c.key}: {curSample[c.key]!=null?curSample[c.key]:'--'}</span>)}
        </div>
      </>}
    </div>
    <div style={{flex:'none',borderTop:'1px solid var(--border-0)',padding:'4px 12px 2px'}}>
      <div style={{display:'flex',justifyContent:'space-between',fontSize:9,color:'var(--fg-3)',letterSpacing:'.08em'}}>
        <span>{t('速率','RATE')}</span>
        <span><span style={{color:'#ffb454'}}>TX {rate.tx.toFixed(0)}</span> / <span style={{color:'#00ff9c'}}>RX {rate.rx.toFixed(0)}</span> B/s</span>
      </div>
      <canvas ref={rateRef} style={{width:'100%',height:30,display:'block'}}></canvas>
    </div>
    <div style={{padding:'4px 10px',borderTop:'1px solid var(--border-0)',fontSize:10,color:'var(--fg-3)',display:'flex',alignItems:'center',gap:10,flex:'none'}}>
      <span>{viewSamples.length}/{win} · 700 ms</span>
      <div style={{flex:1}}></div>
      <span style={{cursor:'pointer',color:'var(--fg-2)'}} onClick={exportCsv} title={t('导出图表数据','export data')}>CSV↓</span>
      <span style={{cursor:'pointer',color:'var(--fg-2)'}} onClick={exportPng} title={t('导出图片','export image')}>PNG↓</span>
      <span>{paused?'PAUSED':running?'STREAMING':'IDLE'}</span>
    </div>
  </Card>;
}
window.PlotPanel=PlotPanel;
