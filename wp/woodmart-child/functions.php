<?php
/**
 * Enqueue script and styles for child theme
 */
function woodmart_child_enqueue_styles() {
	$child_style_path = get_stylesheet_directory() . '/style.css';
	$child_style_ver  = file_exists( $child_style_path ) ? filemtime( $child_style_path ) : woodmart_get_theme_info( 'Version' );

	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'woodmart-style' ), $child_style_ver );
}
add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 10010 );

function mrocioa_vx_js() {
	if ( ! is_page( 13289 ) && ! is_page_template( 'page-s5pro-landing.php' ) ) return;
	?>
	<style>.vx-page .tvclock{display:none}@media (max-width:1020px){.vx-page .vx-wrap{padding:32px 14px 0}.vx-page .scene{grid-template-columns:1fr 1fr;gap:16px}.vx-page .scene .center-col{grid-column:1/-1;order:-1}.vx-page .ph{zoom:.7}.vx-page .rmt{zoom:.9}.vx-page .devrow,.vx-page .conn-row{gap:6px}.vx-page .dev{padding:8px 3px 7px}.vx-page .dev .dname{font-size:.54rem}.vx-page .dev .dport{font-size:.46rem}.vx-page .port .plabel{font-size:.46rem}.vx-page .tvset{width:80%}.vx-page .sw{zoom:.5}.vx-page .scene{gap:10px}.vx-page .vx-wrap{padding:20px 14px 0}.vx-page .vx-title{font-size:1.3rem}.vx-page .vx-sub{font-size:.85rem}.vx-page .conn-v{height:14px}.vx-page .conn-row{height:14px}.vx-page .dev svg{width:16px;height:16px;margin-bottom:3px}.vx-page .dev{padding:6px 2px 5px}.vx-page .dev .dname{font-size:.5rem}.vx-page .dev .dport{font-size:.44rem}.vx-page .dev{zoom:.5}}</style>
	<script data-cfasync="false">document.addEventListener("DOMContentLoaded",function(){if(!document.getElementById("tvscreen"))return;if(window.__vxI)return;window.__vxI=1; var DEV={1:{name:"SOUNDBAR",sub:"DOLBY ATMOS - ARC",accent:"#F5A623",bg:"radial-gradient(ellipse at 50% 20%,#4A3208 0%,#06080D 72%)",tiles:["Movie","Music","Night","Voice","Bass+","Flat"],icon:"sb"},2:{name:"PLAYSTATION 5",sub:"GAME MODE - 4K 120",accent:"#7FB2FF",bg:"radial-gradient(ellipse at 30% 20%,#103A8C 0%,#06080D 70%)",tiles:["Spider-Man 2","GT7","God of War","FF VII","Store","Media"],icon:"ps"},3:{name:"XBOX SERIES X",sub:"GAME MODE - 4K 120",accent:"#7CE89B",bg:"radial-gradient(ellipse at 70% 25%,#0E5A1F 0%,#06080D 70%)",tiles:["Halo","Forza","Game Pass","Starfield","Store","Apps"],icon:"xb"},4:{name:"APPLE TV 4K",sub:"STREAMING - DOLBY VISION",accent:"#D8DCE4",bg:"radial-gradient(ellipse at 50% 15%,#3A3F4B 0%,#06080D 72%)",tiles:["TV+","Netflix","YouTube","Music","Arcade","Photos"],icon:"tv"},5:{name:"GAMING PC",sub:"DISPLAYPORT ALT - 120HZ",accent:"#C2A8FF",bg:"radial-gradient(ellipse at 65% 70%,#4A2A8C 0%,#06080D 70%)",tiles:["Steam","Discord","OBS","Browser","Files","Settings"],icon:"pc"}}; var ICONS={sb:"<svg viewBox='0 0 24 24' fill='none' stroke='ACC' stroke-width='1.5'><rect x='3' y='9' width='18' height='6' rx='3'/><circle cx='8' cy='12' r='1.2' fill='ACC'/><circle cx='16' cy='12' r='1.2' fill='ACC'/></svg>",ps:"<svg viewBox='0 0 24 24' fill='none' stroke='ACC' stroke-width='1.5'><path d='M6 9h4M8 7v4'/><path d='M4 14c0-2 1.5-3.5 8-3.5s8 1.5 8 3.5c0 2.5-1 5-2.5 5s-2-1.8-5.5-1.8S8 19 6.5 19 4 16.5 4 14Z'/></svg>",xb:"<svg viewBox='0 0 24 24' fill='none' stroke='ACC' stroke-width='1.5'><circle cx='12' cy='12' r='8'/><path d='M7.5 7.5c2.5 2 6.5 6 9 9M16.5 7.5c-2.5 2-6.5 6-9 9'/></svg>",tv:"<svg viewBox='0 0 24 24' fill='none' stroke='ACC' stroke-width='1.5'><rect x='4' y='8' width='16' height='9' rx='2'/><path d='M9 20h6'/></svg>",pc:"<svg viewBox='0 0 24 24' fill='none' stroke='ACC' stroke-width='1.5'><rect x='3' y='4' width='18' height='12' rx='1.5'/><path d='M8 20h8M12 16v4'/></svg>"}; var RES=["4K 120HZ","8K 60HZ","4K 60HZ","1080P 120HZ"]; var st={power:false,input:1,auto:true,res:0,vol:12,tvport:2,dev:{1:false,2:false,3:false,4:false,5:false},sel:{1:0,2:0,3:0,4:0,5:0}}; var osdT=null,sbT=null; function $(i){return document.getElementById(i)} var screenEl=$("tvscreen"); for(var k=1;k<=5;k++){var d=DEV[k];var sc=document.createElement("div");sc.className="tvscene";sc.id="tvsc"+k;sc.style.background=d.bg;var tiles="";for(var t=0;t<d.tiles.length;t++){tiles+="<div class='tile' data-i='"+t+"' style='background:linear-gradient(160deg,"+d.accent+"22,"+d.accent+"0d)'>"+d.tiles[t]+"</div>";}sc.innerHTML="<div class='tvtop'><span class='tvname' style='color:"+d.accent+"'>"+ICONS[d.icon].split("ACC").join(d.accent)+d.name+"</span><span class='tvclock'>11:47</span></div><div class='tiles' data-dev='"+k+"'>"+tiles+"</div><div class='tvhint'>"+d.sub+"</div>";screenEl.appendChild(sc);} var tvOff=$("tvOff"),hudRes=$("hudRes"),tvosd=$("tvosd"),connMain=$("connMain"),appTv=$("appTv"),appTvName=$("appTvName"),tglAuto=$("tglAuto"),tglMode=$("tglMode"),btnAutoR=$("btnAutoR"),sbVol=$("sbVol"),sbFill=$("sbFill"),sbNum=$("sbNum"),stInput=$("stInput"),stName=$("stName"),stRes=$("stRes"),stAuto=$("stAuto"),stVol=$("stVol"); function flash(el){if(!el)return;el.classList.remove("syncflash");void el.offsetWidth;el.classList.add("syncflash");} function osd(msg,ms){if(!st.power)return;tvosd.textContent=msg;tvosd.classList.add("show");clearTimeout(osdT);osdT=setTimeout(function(){tvosd.classList.remove("show")},ms||1300);} function render(src){for(var i=1;i<=5;i++){$("tvsc"+i).classList.toggle("on",st.power&&st.input===i&&!!st.dev[i]);var sc=$("tvsc"+i);var tl=sc.querySelectorAll(".tile");for(var j=0;j<tl.length;j++){tl[j].classList.toggle("sel",j===st.sel[i]);}} tvOff.classList.toggle("on",!st.power);hudRes.textContent=vxRes();vxSig(); document.querySelectorAll("#swPorts .port").forEach(function(p){p.classList.toggle("active",+p.dataset.port===st.input);}); document.querySelectorAll("#connRow i").forEach(function(c){c.classList.toggle("active",+c.dataset.port===st.input);}); document.querySelectorAll("#devRow .dev").forEach(function(d){var dn=+d.dataset.dev;d.classList.toggle("active",dn===st.input);d.classList.toggle("on",!!st.dev[dn]);d.classList.toggle("off",!st.dev[dn]);}); appTvName.textContent=st.power?DEV[st.input].name:"STANDBY";appTv.classList.toggle("off",!st.power); tglAuto.classList.toggle("on",st.auto);btnAutoR.classList.toggle("active",st.auto); document.querySelectorAll(".rbt-num").forEach(function(b){b.classList.toggle("active",+b.dataset.input===st.input);}); document.querySelectorAll("#appSrcs .src").forEach(function(b){b.classList.toggle("active",+b.dataset.input===st.input);}); stInput.textContent=st.input;stName.textContent=DEV[st.input].name;stRes.textContent=vxRes();stAuto.textContent=st.auto?"ON":"OFF";stVol.textContent=st.vol;document.querySelectorAll('#tvPorts .tvport').forEach(function(p){p.classList.toggle('active',+p.dataset.tvport===st.tvport);});var ce=document.getElementById('connElbow');if(ce){ce.classList.toggle('port1',st.tvport===1);}var sb1=document.querySelector('#devRow .dev[data-dev="1"]');if(sb1){sb1.classList.toggle('noarc',st.tvport!==2);}var sbn=document.getElementById('swBtn');if(sbn){sbn.classList.toggle('auto-on',st.auto);} if(src==="remote"){flash(document.querySelector("#appSrcs .src[data-input="+JSON.stringify(""+st.input)+"]"));}else if(src==="app"){flash(document.querySelector(".rbt-num[data-input="+JSON.stringify(""+st.input)+"]"));} if(src){connMain.classList.remove("flash");void connMain.offsetWidth;connMain.classList.add("flash");}} function setInput(n,src){closeApp();st.input=n;render(src);if(n===1&&st.tvport!==2){osd("TO USE THE SOUNDBAR, CONNECT THE TV EARC PORT (HDMI 2) - TO TEST A NON-ARC PORT, SWITCH THE S5 PRO TO ANOTHER INPUT",5500);}else{osd("HDMI IN "+n+" - "+DEV[n].name);}vxFmt();} function cycle(d){var n=st.input+d;if(n>5)n=1;if(n<1)n=5;setInput(n);} function toggleAuto(){st.auto=!st.auto;render();} function move(dx,dy){if(!st.power)return;if(st.dev&&!st.dev[st.input]){osd("NO SIGNAL - POWER ON THE SOURCE DEVICE",2500);return;}if(st.appv){osd("PRESS BACK TO RETURN HOME",2000);return;}if(st.input===3||st.input===5){osd(DEV[st.input].name+" IGNORES CEC NAVIGATION - USE ITS OWN CONTROLLER",3500);return;}var n=st.input;var s=st.sel[n];var col=s%3+dx;var row=Math.floor(s/3)+dy;if(col<0)col=0;if(col>2)col=2;if(row<0)row=0;if(row>1)row=1;var ns=row*3+col;if(ns===s)return;st.sel[n]=ns;var tt=document.querySelector('#tvsc'+n+' .tiles');if(tt){if(tt.children[s])tt.children[s].classList.remove('sel');if(tt.children[ns])tt.children[ns].classList.add('sel');}}function okPress(){var n=st.input;var nm=DEV[n].tiles[st.sel[n]];if(st.appv){osd("DEMO ENDS HERE - PRESS BACK TO RETURN HOME",3500);return;}if(n===1){osd("SOUNDBAR MENUS VARY BY MODEL - USE ITS REMOTE OR APP (VOLUME STILL WORKS VIA EARC CEC)",4500);return;}if(n===3||n===5){osd(DEV[n].name+" IGNORES CEC OK/PLAYBACK - USE ITS OWN CONTROLLER",3500);return;}openApp(n,nm);}function volume(d){if(!st.power)return;if(st.dev&&!st.dev[1]){if(st.tvv===undefined)st.tvv=12;st.tvv=Math.max(0,Math.min(30,st.tvv+d));osd("TV VOLUME "+st.tvv+" - VIA CEC: SAMSUNG & SONY TVs ONLY, NOT LG",2600);return;}if(st.tvport!==2){osd('NO ARC - PLUG INTO HDMI 2 (eARC)');var s1=document.querySelector('#devRow .dev[data-dev="1"]');if(s1){s1.classList.remove('syncflash');void s1.offsetWidth;s1.classList.add('syncflash');}return;}st.vol=Math.max(0,Math.min(30,st.vol+d));sbFill.style.width=(st.vol/30*100)+"%";sbNum.textContent="VOL "+st.vol;sbVol.classList.add("live");flash(document.querySelector(".dev[data-dev=\"1\"]"));clearTimeout(sbT);sbT=setTimeout(function(){sbVol.classList.remove("live")},1600);stVol.textContent=st.vol;osd("SOUNDBAR VOL "+st.vol+" - ARC");} function setTvPort(n){st.tvport=n;render();osd(n===2?'TV INPUT: HDMI 2 - ARC/eARC ACTIVE':'TV INPUT: HDMI 1 - NO ARC');vxFmt();}function handle(a){switch(a){case "power":var pdv=document.querySelector('.dev-pwr[data-devpwr="'+st.input+'"]');if(st.dev&&!st.dev[st.input]){if(pdv)pdv.click();st.power=true;render();vxFmt();}else{if(pdv&&st.dev&&st.dev[st.input])pdv.click();st.power=false;closeApp();render();vxFmt();}break;case "chup":cycle(1);break;case "chdown":cycle(-1);break;case "auto":toggleAuto();break;case "mode":var bin=st._binClick;st._binClick=0;if(st.input===1){osd("ARC AUDIO CHANNEL - OUTPUT MODE NOT APPLICABLE",2500);break;}if(!st.md)st.md={};if(bin){st.md[st.input]=(st.md[st.input]||0)>0?0:1;}else{st.md[st.input]=((st.md[st.input]||0)+1)%3;}render();osd("OUTPUT MODE - "+(["PASSTHROUGH","4K 60HZ","1080P"][st.md[st.input]]));vxFmt();break;case "up":move(0,-1);break;case "down":move(0,1);break;case "left":move(-1,0);break;case "right":move(1,0);break;case "ok":okPress();break;case "back":if(st.power&&st.appv){closeApp();osd("HOME");}break;case "volup":volume(1);break;case "voldown":volume(-1);break;}} var swb=document.getElementById('swBtn');var hbT=null,hbF=false;if(swb){swb.addEventListener('pointerdown',function(e){e.preventDefault();if(hbT)return;hbF=false;swb.classList.add('holding');osd('HOLD 3S TO TOGGLE AUTO');hbT=setTimeout(function(){hbF=true;hbT=null;swb.classList.remove('holding');toggleAuto();osd(st.auto?'AUTO SCAN: ON':'AUTO SCAN: OFF');},3000);});swb.addEventListener('pointerup',function(){swb.classList.remove('holding');if(hbT){clearTimeout(hbT);hbT=null;if(!hbF){st.input=st.input%5+1;render();vxFmt();osd('HDMI IN '+st.input+' - '+DEV[st.input].name);}}});swb.addEventListener('pointerleave',function(){swb.classList.remove('holding');if(hbT){clearTimeout(hbT);hbT=null;}});swb.addEventListener('pointercancel',function(){swb.classList.remove('holding');if(hbT){clearTimeout(hbT);hbT=null;}});}document.querySelector(".vx-page").addEventListener("click",function(e){var b=e.target.closest("button");if(b){if(b.dataset.tvport){setTvPort(+b.dataset.tvport);return;}if(b.dataset.input){setInput(+b.dataset.input,b.closest("#appSrcs")?"app":"remote");return;}if(b.dataset.act){handle(b.dataset.act);return;}if(b.dataset.devpwr){var dpn=+b.dataset.devpwr;st.dev[dpn]=!st.dev[dpn];if(dpn>=2){st.dev[1]=st.dev[dpn];}if(st.dev[dpn]){if(!st.power){st.power=true;}if(st.input===dpn)vxFmt();if(st.auto&&dpn!==4&&st.input!==dpn){var prevIn=st.input;st.input=dpn;render();vxFmt();if(dpn===3){osd('XBOX requires settings to support auto-switching: General --> Power options --> Active Shutdown(energy saving) --> Choose Turn off console',8000);}else if(dpn===1){osd('AUTO - SIGNAL IN 1 - SOUNDBAR');clearTimeout(window.__sbT);window.__sbT=setTimeout(function(){if(st.input===1){st.input=prevIn;render();vxFmt();osd('SOUNDBAR CONFIGURED - SWITCHING BACK TO '+DEV[prevIn].name,5000);}},5000);}else{osd('AUTO - SIGNAL IN '+dpn+' - '+DEV[dpn].name);}}else{render();if(dpn===4&&st.auto){osd('APPLE TV & FIRE TV DO NOT SUPPORT AUTO-SWITCHING',5000);}else{osd(DEV[dpn].name+' - POWER ON');}}}else{render();osd(DEV[dpn].name+' - POWER OFF');}return;}}}); sbFill.style.width=(st.vol/30*100)+"%";render();
var __appHost=null;function closeApp(){if(!st.appv)return;st.appv=0;if(__appHost){__appHost.remove();__appHost=null;}}
function __hue(s){var h=0;for(var i=0;i<s.length;i++){h=(h*31+s.charCodeAt(i))%360;}return h;}
var __GL={"play":"<path d='M8 5v14l11-7z'/>","music":"<path d='M9 18V5l12-2v13' fill='none' stroke-width='2'/><circle cx='6' cy='18' r='3'/><circle cx='18' cy='16' r='3'/>","moon":"<path d='M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z'/>","bass":"<path d='M3 10v4h4l5 5V5L7 10H3z'/><path d='M16 8a5 5 0 0 1 0 8' fill='none' stroke-width='2'/>","eq":"<rect x='4' y='10' width='3' height='10'/><rect x='10' y='4' width='3' height='16'/><rect x='16' y='8' width='3' height='12'/>","bag":"<path d='M6 7h12l1 13H5L6 7z'/><path d='M9 7a3 3 0 0 1 6 0' fill='none' stroke-width='2'/>","gear":"<circle cx='12' cy='12' r='3'/><path d='M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M19.1 4.9L17 7M7 17l-2.1 2.1' fill='none' stroke-width='2'/>","grid":"<rect x='4' y='4' width='7' height='7' rx='1'/><rect x='13' y='4' width='7' height='7' rx='1'/><rect x='4' y='13' width='7' height='7' rx='1'/><rect x='13' y='13' width='7' height='7' rx='1'/>","photo":"<rect x='3' y='5' width='18' height='14' rx='2' fill='none' stroke-width='2'/><circle cx='9' cy='10' r='2'/><path d='M5 18l5-5 4 4 3-3 2 2' fill='none' stroke-width='2'/>","globe":"<circle cx='12' cy='12' r='9' fill='none' stroke-width='2'/><path d='M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18' fill='none' stroke-width='2'/>","chat":"<path d='M4 5h16v11H8l-4 4V5z'/>","cam":"<rect x='3' y='7' width='13' height='10' rx='2'/><path d='M16 11l5-3v8l-5-3z'/>","folder":"<path d='M3 6h6l2 2h10v11H3V6z'/>","pad":"<rect x='2' y='8' width='20' height='9' rx='4.5' fill='none' stroke-width='2'/><path d='M7 11v3M5.5 12.5h3' fill='none' stroke-width='1.6'/><circle cx='16' cy='11.5' r='1'/><circle cx='18.5' cy='13.5' r='1'/>"};
function __gKey(nm){var s=nm.toLowerCase();if(s.indexOf("music")>-1||s==="voice")return"music";if(s==="movie"||s==="media"||s==="tv+"||s==="netflix"||s==="youtube")return"play";if(s==="night")return"moon";if(s.indexOf("bass")>-1)return"bass";if(s==="flat")return"eq";if(s==="store")return"bag";if(s==="settings")return"gear";if(s==="apps"||s==="arcade")return"grid";if(s==="photos")return"photo";if(s==="browser")return"globe";if(s==="discord")return"chat";if(s==="obs")return"cam";if(s==="files")return"folder";if(s==="game pass")return"pad";return null;}
function __tIcon(nm){var k=__gKey(nm);if(k)return "<svg viewBox='0 0 24 24' fill='currentColor' stroke='currentColor' stroke-width='0'>"+__GL[k]+"</svg>";var p=nm.split(" ");var mono=p.length>1?(p[0].charAt(0)+p[1].charAt(0)):nm.slice(0,2);return "<span class='tmono'>"+mono.toUpperCase()+"</span>";}
function __chip(nm){var hue=__hue(nm);return "<span class='ticon' style='background:linear-gradient(145deg,hsl("+hue+",65%,45%),hsl("+((hue+50)%360)+",65%,30%))'>"+__tIcon(nm)+"</span>";}
function __cards(hue){var out="";for(var i=0;i<4;i++){var h=(hue+i*25)%360;out+="<span class='vx-apcd' style='background:linear-gradient(150deg,hsl("+h+",55%,32%),hsl("+h+",55%,16%))'></span>";}return out;}
function openApp(n,nm){var host=document.getElementById("tvscreen");if(!host)return;closeApp();var hue=__hue(nm);var el=document.createElement("div");el.className="vx-appv";el.innerHTML="<div class='vx-aphd'>"+__chip(nm)+"<span>"+nm+"</span></div><div class='vx-aphero' style='background:linear-gradient(135deg,hsl("+hue+",70%,26%),hsl("+((hue+40)%360)+",70%,10%))'><span>"+nm.toUpperCase()+"</span></div><div class='vx-aprow'>"+__cards(hue)+"</div>";host.appendChild(el);__appHost=el;st.appv=1;osd("LAUNCH - "+nm);setTimeout(function(){if(st.appv)osd("DEMO ENDS HERE - PRESS BACK TO RETURN HOME",4000);},1300);}
var __tcss=document.createElement("style");__tcss.textContent=".tile{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px}.ticon{width:34px;height:34px;border-radius:9px;display:inline-flex;align-items:center;justify-content:center;color:#fff;flex:0 0 auto;box-shadow:0 2px 6px rgba(0,0,0,.35)}.ticon svg{width:18px;height:18px}.tmono{font-size:13px;font-weight:700;letter-spacing:.5px}.tlabel{font-size:9px;opacity:.85;max-width:92%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}#tvosd{z-index:60;font-size:13px;line-height:1.45;padding:7px 13px;max-width:82%;border-radius:7px;letter-spacing:.3px}.vx-appv{position:absolute;left:0;top:0;right:0;bottom:0;z-index:40;background:#04060B;display:flex;flex-direction:column;padding:4.5% 5%;gap:4%;animation:vxain .35s ease}.vx-aphd{display:flex;align-items:center;gap:9px;color:#fff;font-size:12px;font-weight:600;letter-spacing:.4px}.vx-aphd .ticon{width:26px;height:26px;border-radius:7px}.vx-aphd .ticon svg{width:14px;height:14px}.vx-aphd .tmono{font-size:10px}.vx-aphero{flex:1;border-radius:8px;display:flex;align-items:flex-end;padding:3% 4%;color:rgba(255,255,255,.92);font-size:17px;font-weight:700;letter-spacing:1px}.vx-aprow{display:flex;gap:3%;height:26%}.vx-apcd{flex:1;border-radius:6px}@keyframes vxain{from{opacity:0;transform:scale(.985)}to{opacity:1;transform:scale(1)}}";document.head.appendChild(__tcss);__tcss.textContent+="#vxfmt{position:absolute;top:10px;right:12px;z-index:45;background:rgba(0,0,0,.55);color:#CFE9F5;font-size:12px;font-weight:600;letter-spacing:.08em;padding:5px 11px;border-radius:6px;opacity:0;transition:opacity .25s;pointer-events:none}#vxfmt.show{opacity:1}.rmt{justify-content:space-between}#appSrcs .src{width:38px;height:38px}.ph-tabs .tab-plus{margin-top:2px}#vxnosig{position:absolute;left:0;top:0;right:0;bottom:0;z-index:30;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;color:#8FA8B8;letter-spacing:.18em;font-size:15px;font-weight:600;opacity:0;transition:opacity .3s;pointer-events:none;background:#05070C}#vxnosig.show{opacity:1}@media(max-width:1020px){.vx-page div.sw{zoom:.59}#devRow .dname{font-size:15px}#devRow .dport{font-size:12px}#devRow .vnum{font-size:12px}.vx-page .sw .sw-logo{font-size:14px}.vx-page .sw .sw-model{font-size:11px}.vx-page .sw .port .plabel{font-size:11px}.vx-page .dev{flex:0 0 auto}.vx-page #devRow{justify-content:center}}";var __fmtEl=document.createElement("div");__fmtEl.id="vxfmt";document.getElementById("tvscreen").appendChild(__fmtEl);var __fmtT=null;var __FMT={2:"4K 120HZ",3:"4K 120HZ",4:"4K 60HZ",5:"8K / 4K 120HZ"};function vxFmt(){if(!st.power){__fmtEl.classList.remove("show");return;}var n=st.input;var t=vxRes();var mm=(n===1)?0:((st.md&&st.md[n])||0);tglMode.classList[mm>0?"add":"remove"]("on");if(!t){__fmtEl.classList.remove("show");return;}__fmtEl.textContent=t;__fmtEl.classList.add("show");clearTimeout(__fmtT);__fmtT=setTimeout(function(){__fmtEl.classList.remove("show");},4000);}function vxSyncH(){var p=document.querySelector(".ph");var r=document.getElementById("remote");if(!p||!r)return;r.style.minHeight="";r.style.zoom="";var pv=p.getBoundingClientRect().height;if(pv<10)return;var z=parseFloat(getComputedStyle(r).zoom)||1;var nat=r.offsetHeight;var rv=nat*z;if(rv>pv+4){r.style.zoom=(pv/nat).toFixed(3);}else if(rv<pv-4){r.style.minHeight=Math.round(pv/z)+"px";}}st.md={};function vxSig(){var e=document.getElementById("vxnosig");if(!e)return;var s=st.power&&st.dev&&!st.dev[st.input];e.classList[s?"add":"remove"]("show");}var __sigEl=document.createElement("div");__sigEl.id="vxnosig";__sigEl.innerHTML="NO SIGNAL<span style='font-size:9px;letter-spacing:.14em;opacity:.65'>POWER ON THE SOURCE DEVICE</span>";document.getElementById("tvscreen").appendChild(__sigEl);vxSig();document.addEventListener("click",function(e){if(e.target&&e.target.closest&&e.target.closest("#tglMode"))st._binClick=1;},true);function vxRes(){var n=st.input;if(st.dev&&!st.dev[n])return "NO SIGNAL";if(n===1)return st.tvport===2?"eARC AUDIO":"NO ARC";var m=(st.md&&st.md[n])||0;if(m===1)return "4K 60HZ";if(m===2)return "1080P";var F={2:"4K 120HZ",3:"4K 120HZ",4:"4K 60HZ",5:"8K / 4K 120HZ"};return F[n];}function vxSyncW(){var swEl=document.querySelector(".sw");var tvf=document.querySelector(".tvframe");if(!swEl||!tvf)return;var devs=document.querySelectorAll("#devRow .dev");var i;if(window.innerWidth>1020){swEl.style.zoom="";for(i=0;i<devs.length;i++)devs[i].style.zoom="";var rw0=document.getElementById("devRow");if(rw0)rw0.style.width="";return;}var tw=tvf.getBoundingClientRect().width;if(tw<100)return;var nz=Math.max(.59,Math.min(1,tw/(swEl.offsetWidth||520)));swEl.style.zoom=nz.toFixed(3);if(devs.length){var rw=document.getElementById("devRow");if(!window.__devNat){devs[0].style.zoom="";window.__devNat=devs[0].offsetWidth||114;}var nat=window.__devNat;var dz=Math.max(.5,Math.min(1,(tw-6*(devs.length-1))/(nat*devs.length)));var need=Math.ceil(nat*devs.length*dz+6*(devs.length-1));if(rw)rw.style.width=Math.max(Math.round(tw),need)+"px";for(i=0;i<devs.length;i++)devs[i].style.zoom=dz.toFixed(3);}}function vxSyncAll(){vxSyncH();vxSyncW();}window.addEventListener("resize",vxSyncAll);window.addEventListener("load",vxSyncAll);setTimeout(vxSyncAll,600);setTimeout(vxSyncAll,1800);vxSyncAll();
[].forEach.call(document.querySelectorAll(".tile"),function(t){var nm=t.textContent.replace(/\s+/g," ").trim();t.innerHTML=__chip(nm)+"<span class='tlabel'>"+nm+"</span>";});
});</script>
	<?php
}
add_action( 'wp_footer', 'mrocioa_vx_js' );


// mrocioa: rebuild Elementor CSS + flush caches on product save
function mrocioa_rebuild_on_product_save($post_id){
    if(wp_is_post_autosave($post_id)||wp_is_post_revision($post_id))return;
    if(class_exists('\\Elementor\\Plugin'))\Elementor\Plugin::$instance->files_manager->clear_cache();
    if(function_exists('w3tc_flush_all'))w3tc_flush_all();
    if(function_exists('wpo_cache_flush'))wpo_cache_flush();
}
add_action('save_post_product','mrocioa_rebuild_on_product_save',20,1);

// mrocioa: collapsible max-height + hide demo page bottom nav
function mrocioa_product_collapsible_height(){
    echo '<style>.elementor-9297 .elementor-element-aa67a64.wd-collapsible-content.e-con:not(.wd-opened){max-height:1160px!important}.page-id-13289 .wd-toolbar,.page-id-13289 [class*=sticky-bottom],.page-id-13289 [class*=mobile-nav]{display:none!important}</style>';
}
add_action('wp_head','mrocioa_product_collapsible_height');

// ============================================================


// ============================================================
// MRO AV SWITCH CATEGORY PAGE — Style A v1
// mro_av_switch_category
// ============================================================

function mro_av_switch_css(){
if(!function_exists('is_product_category')||!is_product_category('av-switches'))return;
echo '<style id="mro-av-new">';
?>
body.term-av-switches .woocommerce-products-header,body.term-av-switches .term-description{display:none!important}
body.term-av-switches{background:#F6F6FB}
.mro-hero{position:relative;overflow:hidden;background:radial-gradient(120% 120% at 80% 0%,#1c1840,#0C0C16 55%);color:#fff;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-hero:before{content:"";position:absolute;inset:0;background:radial-gradient(600px 400px at 90% 20%,rgba(55,213,242,.22),transparent 60%),radial-gradient(520px 400px at 8% 90%,rgba(123,108,246,.30),transparent 60%);pointer-events:none}
.mro-hero-in{position:relative;z-index:1;max-width:1240px;margin:0 auto;padding:84px 28px 70px;display:grid;grid-template-columns:1.05fr .95fr;gap:40px;align-items:center;min-height:540px}
.mro-eyebrow{display:inline-flex;align-items:center;gap:8px;font-family:'IBM Plex Mono',monospace;font-size:13px;letter-spacing:.16em;text-transform:uppercase;color:#37D5F2;border:1px solid rgba(55,213,242,.35);padding:7px 14px;border-radius:100px;margin-bottom:24px}
.mro-pulse{width:7px;height:7px;border-radius:50%;background:#37D5F2;display:inline-block;animation:mropulse 2s infinite}
@keyframes mropulse{0%{box-shadow:0 0 0 0 rgba(55,213,242,.6)}70%{box-shadow:0 0 0 9px rgba(55,213,242,0)}100%{box-shadow:0 0 0 0 rgba(55,213,242,0)}}
.mro-hero h1{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:clamp(2.6rem,5.4vw,4.4rem);line-height:1.1;letter-spacing:-.01em;color:#fff;margin:0 0 14px}
.mro-grad{background:linear-gradient(95deg,#7B6CF6,#37D5F2);-webkit-background-clip:text;background-clip:text;color:transparent}
.mro-lede{font-size:18px;color:#c7c7da;max-width:480px;margin:0 0 34px}
.mro-ctas{display:flex;gap:14px;flex-wrap:wrap}
.mro-btn-p,.mro-btn-g{display:inline-flex;align-items:center;font-weight:600;font-size:15.5px;padding:15px 28px;border-radius:13px;cursor:pointer;border:none;font-family:'Inter',sans-serif;text-decoration:none;transition:.2s}
.mro-btn-p{background:linear-gradient(95deg,#7B6CF6,#37D5F2);color:#fff;box-shadow:0 14px 34px -10px rgba(123,108,246,.7)}
.mro-btn-g{background:rgba(255,255,255,.06);color:#fff;border:1px solid rgba(255,255,255,.2)}
.mro-trust{display:flex;gap:22px;margin-top:34px;font-size:13.5px;color:#a9a9c2;flex-wrap:wrap;list-style:none;padding:0;margin-left:0}
.mro-trust li{display:inline-flex;align-items:center;gap:7px}
.mro-trust li:before{content:"\2713 ";color:#37D5F2;font-weight:700}
.mro-visual{position:relative;display:grid;place-items:center}
.mro-glow{position:absolute;width:380px;height:380px;background:radial-gradient(circle,rgba(123,108,246,.45),transparent 65%);filter:blur(20px)}
.mro-float{position:relative;width:min(100%,420px);background:#fff;border-radius:28px;padding:16px;box-shadow:0 44px 90px -24px rgba(0,0,0,.6);animation:mrofloat 6s ease-in-out infinite}
@keyframes mrofloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-14px)}}
.mro-float img{display:block;width:100%;border-radius:14px}
.mro-fbadge{position:absolute;top:-3%;right:-3%;z-index:2;background:#fff;color:#0C0C16;font-family:'IBM Plex Mono',monospace;font-weight:500;font-size:12px;padding:8px 13px;border-radius:11px;box-shadow:0 12px 30px rgba(0,0,0,.4);transform:rotate(4deg)}
.mro-fbadge b{color:#7B6CF6}
.mro-stats-bar{background:#15151F;border-top:1px solid rgba(255,255,255,.06);width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-stats-in{max-width:1240px;margin:0 auto;padding:30px 28px;display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.mro-stat{text-align:center;color:#fff;padding:6px}
.mro-stat .n{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:34px;background:linear-gradient(95deg,#7B6CF6,#37D5F2);-webkit-background-clip:text;background-clip:text;color:transparent}
.mro-stat .l{font-size:13px;color:#9a9ab0;font-family:'IBM Plex Mono',monospace;letter-spacing:.04em;margin-top:4px}
.mro-toolbar{background:#F6F6FB;border-bottom:1px solid #E7E7F0;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-toolbar-in{max-width:1240px;margin:0 auto;padding:20px 28px;display:flex;align-items:center;gap:14px;flex-wrap:wrap}
.mro-chip{font-size:14px;font-weight:500;padding:9px 18px;border-radius:100px;background:#fff;border:1px solid #E7E7F0;cursor:pointer;color:#44444f;text-decoration:none;transition:.18s}
.mro-chip.on{background:#0C0C16;color:#fff;border-color:#0C0C16}
body.term-av-switches .wd-products{grid-template-columns:repeat(4,1fr)!important}
body.term-av-switches .product-grid-item{background:transparent!important;border:none!important;box-shadow:none!important;text-align:center}
body.term-av-switches .product-element-top{background:#F1F1F6!important;border-radius:16px!important;aspect-ratio:1/1!important;display:grid!important;place-items:center!important;padding:12px!important;overflow:hidden!important;min-height:0!important}
body.term-av-switches .product-element-top img{object-fit:contain!important;width:100%!important;height:100%!important;transition:.35s ease!important}
body.term-av-switches .product-grid-item:hover .product-element-top img{transform:scale(1.05)!important}
body.term-av-switches .wd-entities-title{font-family:'Inter',sans-serif!important;font-size:15px!important;font-weight:600!important;line-height:1.35!important;text-align:center!important;display:-webkit-box!important;-webkit-line-clamp:2!important;-webkit-box-orient:vertical!important;overflow:hidden!important;min-height:40px!important}
body.term-av-switches .price{font-family:'Space Grotesk',sans-serif!important;font-size:20px!important;font-weight:700!important;color:#7B6CF6!important;text-align:center!important;justify-content:center!important}
body.term-av-switches .button.add_to_cart_button{background:#0C0C16!important;color:#fff!important;border-radius:100px!important;padding:11px 28px!important;font-weight:600!important;font-size:14px!important;font-family:'Inter',sans-serif!important;border:none!important;margin-top:10px!important;min-width:0!important;min-height:0!important}
body.term-av-switches .button.add_to_cart_button:hover{background:#7B6CF6!important}
.mro-vals-wrap{background:#fff;border-top:1px solid #E7E7F0;padding:60px 0;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-vals{max-width:1240px;margin:0 auto;padding:0 28px;display:grid;grid-template-columns:repeat(4,1fr);gap:26px}
.mro-val .ic{width:48px;height:48px;border-radius:13px;background:linear-gradient(135deg,rgba(123,108,246,.12),rgba(55,213,242,.12));display:grid;place-items:center;color:#7B6CF6;margin-bottom:15px}
.mro-val h4{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:16px;margin:0 0 6px;color:#16161F}
.mro-val p{font-size:13.5px;color:#6B6B7B;line-height:1.55;margin:0}
.mro-cta-band{background:linear-gradient(95deg,#7B6CF6,#37D5F2);color:#fff;text-align:center;padding:64px 28px;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-cta-band h2{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.1;margin:0 0 12px;color:#fff}
.mro-cta-band p{color:rgba(255,255,255,.88);max-width:520px;margin:0 auto 26px;font-size:16px}
.mro-cta-band a{display:inline-block;background:#fff;color:#0C0C16;font-weight:600;padding:15px 30px;border-radius:13px;text-decoration:none}
@media(max-width:900px){
.mro-hero-in{grid-template-columns:1fr;padding:54px 28px;min-height:auto;text-align:center;gap:30px}
.mro-visual{order:-1}.mro-float{width:280px}
.mro-lede,.mro-ctas,.mro-trust{margin-left:auto;margin-right:auto;justify-content:center}
.mro-stats-in{grid-template-columns:repeat(2,1fr)}
body.term-av-switches .wd-products{grid-template-columns:repeat(2,1fr)!important}
.mro-vals{grid-template-columns:repeat(2,1fr)}
}
<?php
echo '</style>';
}
add_action('wp_head','mro_av_switch_css');
function mro_av_switch_before(){
if(!function_exists('is_product_category')||!is_product_category('av-switches'))return;
?>
<section class="mro-hero"><div class="mro-hero-in"><div><span class="mro-eyebrow"><span class="mro-pulse"></span> AV Switch Collection</span><h1>One screen.<br><em class="mro-grad">Every source.</em></h1><p class="mro-lede">8K HDMI 2.1 switchers that flip between console, streamer, PC and player with one tap — full 48Gbps passthrough and no more swapping cables.</p><div class="mro-ctas"><a class="mro-btn-p" href="/product/8k-hdmi-switch-5-port-earc/">Shop the S5 Pro &rarr;</a></div><ul class="mro-trust"><li>48Gbps full bandwidth</li><li>Smart EDID / CEC</li><li>12-month warranty</li></ul></div><div class="mro-visual"><div class="mro-glow"></div><div class="mro-float"><span class="mro-fbadge">8K@60Hz &middot; <b>48Gbps</b></span><img src="https://mrocioa.com/wp-content/uploads/2025/08/mrocioa-8k-hdmi-2.1-switch-521arc-600x600.jpg" alt="MROCIOA S5 Pro"></div></div></div></section>
<div class="mro-stats-bar"><div class="mro-stats-in"><div class="mro-stat"><div class="n">48Gbps</div><div class="l">Full bandwidth</div></div><div class="mro-stat"><div class="n">8K</div><div class="l">@60Hz / 4K@120Hz</div></div><div class="mro-stat"><div class="n">50k+</div><div class="l">Units shipped</div></div><div class="mro-stat"><div class="n">4.8&#9733;</div><div class="l">Avg rating</div></div></div></div>
<div class="mro-toolbar"><div class="mro-toolbar-in"><a class="mro-chip on" href="#">All</a><a class="mro-chip" href="#">HDMI 2.1 / 8K</a><a class="mro-chip" href="#">4K@120Hz</a><a class="mro-chip" href="#">3-in-1</a><a class="mro-chip" href="#">5-in-1</a><a class="mro-chip" href="#">App control</a></div></div>
<?php
}
add_action('woocommerce_before_main_content','mro_av_switch_before',5);
function mro_av_switch_after(){
if(!function_exists('is_product_category')||!is_product_category('av-switches'))return;
?>
<div class="mro-vals-wrap"><div class="mro-vals"><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg></div><h4>48Gbps passthrough</h4><p>Pure hardware path, no compression. Stable 8K@60Hz and 4K@120Hz output.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><h4>Smart EDID / CEC</h4><p>Prevents black screens and handshake drops; CEC lets one remote run all devices.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div><h4>12-month warranty</h4><p>Every switch covered for a full year with responsive support.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div><h4>Worldwide shipping</h4><p>Free over $35, multiple warehouses, full tracking.</p></div></div></div>
<div class="mro-cta-band"><h2>Not sure which one?</h2><p>Tell us how many devices you're connecting and the resolution you need — we'll match you to the right switch in 30 seconds.</p><a href="/contact-us/">Find my switch &rarr;</a></div>
<?php
}
add_action('woocommerce_after_main_content','mro_av_switch_after',30);


// ============================================================
// MRO AV CABLES CATEGORY PAGE — Style A v1
// ============================================================
function mro_av_cables_css(){
if(!function_exists('is_product_category')||!is_product_category('av-cables'))return;
echo '<style id="mro-avc-new">';
?>
body.term-av-cables{background:#F6F6FB}
body.term-av-cables .term-description,body.term-av-cables .woocommerce-products-header{display:none!important}
body.term-av-cables .wd-products{grid-template-columns:repeat(4,1fr)!important}
body.term-av-cables .product-grid-item{background:transparent!important;border:none!important;box-shadow:none!important;text-align:center}
body.term-av-cables .product-element-top{background:#F1F1F6!important;border-radius:16px!important;aspect-ratio:1/1!important;display:grid!important;place-items:center!important;padding:12px!important;overflow:hidden!important;min-height:0!important}
body.term-av-cables .product-element-top img{object-fit:contain!important;width:100%!important;height:100%!important;transition:.35s ease!important}
body.term-av-cables .product-grid-item:hover .product-element-top img{transform:scale(1.05)!important}
body.term-av-cables .wd-entities-title{font-family:'Inter',sans-serif!important;font-size:15px!important;font-weight:600!important;line-height:1.35!important;text-align:center!important;display:-webkit-box!important;-webkit-line-clamp:2!important;-webkit-box-orient:vertical!important;overflow:hidden!important;min-height:40px!important}
body.term-av-cables .price{font-family:'Space Grotesk',sans-serif!important;font-size:20px!important;font-weight:700!important;color:#7B6CF6!important;text-align:center!important;justify-content:center!important}
body.term-av-cables .button.add_to_cart_button{background:#0C0C16!important;color:#fff!important;border-radius:100px!important;padding:11px 28px!important;font-weight:600!important;font-size:14px!important;font-family:'Inter',sans-serif!important;border:none!important;margin-top:10px!important;min-width:0!important;min-height:0!important}
body.term-av-cables .button.add_to_cart_button:hover{background:#7B6CF6!important}
@media(max-width:900px){body.term-av-cables .wd-products{grid-template-columns:repeat(2,1fr)!important}.mro-hero-in{grid-template-columns:1fr;padding:54px 28px;min-height:auto;text-align:center;gap:30px}.mro-visual{order:-1}.mro-float{width:280px}.mro-lede,.mro-ctas,.mro-trust{justify-content:center}}
<?php
echo '</style>';
}
add_action('wp_head','mro_av_cables_css');
function mro_av_cables_before(){
if(!function_exists('is_product_category')||!is_product_category('av-cables'))return;
?>
<section class="mro-hero"><div class="mro-hero-in"><div><span class="mro-eyebrow"><span class="mro-pulse"></span> AV Cables Collection</span><h1>Crystal clear.<br><em class="mro-grad">Every connection.</em></h1><p class="mro-lede">8K HDMI 2.1 cables built for PS5, Xbox, Apple TV and 8K TVs &mdash; full 48Gbps bandwidth, eARC audio and zero signal loss.</p><div class="mro-ctas"><a class="mro-btn-p" href="/product/8k-hdmi-cable-10-feet/">Shop 8K Cables &rarr;</a></div><ul class="mro-trust"><li>48Gbps bandwidth</li><li>eARC / ARC ready</li><li>12-month warranty</li></ul></div><div class="mro-visual"><div class="mro-glow"></div><div class="mro-float"><span class="mro-fbadge">8K@60Hz &middot; <b>eARC</b></span><img src="https://mrocioa.com/wp-content/uploads/2025/08/8K-HDMI-CABLE-2.1-4k-120hz-main-600x600.jpg" alt="MROCIOA 8K HDMI Cable"></div></div></div></section>
<div class="mro-stats-bar"><div class="mro-stats-in"><div class="mro-stat"><div class="n">48Gbps</div><div class="l">Full bandwidth</div></div><div class="mro-stat"><div class="n">8K</div><div class="l">@60Hz / 4K@120Hz</div></div><div class="mro-stat"><div class="n">eARC</div><div class="l">Lossless audio</div></div><div class="mro-stat"><div class="n">4.8&#9733;</div><div class="l">Avg rating</div></div></div></div>
<div class="mro-toolbar"><div class="mro-toolbar-in"><a class="mro-chip on" href="#">All</a><a class="mro-chip" href="#">HDMI 2.1</a><a class="mro-chip" href="#">4K@120Hz</a><a class="mro-chip" href="#">eARC</a><a class="mro-chip" href="#">8K</a><a class="mro-chip" href="#">Braided</a></div></div>
<?php
}
add_action('woocommerce_before_main_content','mro_av_cables_before',5);
function mro_av_cables_after(){
if(!function_exists('is_product_category')||!is_product_category('av-cables'))return;
?>
<div class="mro-vals-wrap"><div class="mro-vals"><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg></div><h4>48Gbps passthrough</h4><p>Pure signal from source to screen — no compression, no signal loss at full rated bandwidth.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><h4>Premium materials</h4><p>Braided nylon jacket, gold-plated connectors and strain relief built to last.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div><h4>12-month warranty</h4><p>Every cable covered for a full year with responsive support.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div><h4>Worldwide shipping</h4><p>Free over $35, multiple warehouses, full tracking.</p></div></div></div>
<div class="mro-cta-band"><h2>Not sure which cable?</h2><p>Tell us your devices and the length you need &mdash; we will point you to the right cable.</p><a href="/contact-us/">Find my cable &rarr;</a></div>
<?php
}
add_action('woocommerce_after_main_content','mro_av_cables_after',30);



// ============================================================
// MRO USB CABLES CATEGORY PAGE — Style A v1
// ============================================================
function mro_usb_cables_css(){
if(!function_exists('is_product_category')||!is_product_category('usb-cables'))return;
echo '<style id="mro-usb-new">';
?>
body.term-usb-cables{background:#F6F6FB}
body.term-usb-cables .term-description,body.term-usb-cables .woocommerce-products-header{display:none!important}
body.term-usb-cables .wd-products{grid-template-columns:repeat(4,1fr)!important}
body.term-usb-cables .product-grid-item{background:transparent!important;border:none!important;box-shadow:none!important;text-align:center}
body.term-usb-cables .product-element-top{background:#F1F1F6!important;border-radius:16px!important;aspect-ratio:1/1!important;display:grid!important;place-items:center!important;padding:12px!important;overflow:hidden!important;min-height:0!important}
body.term-usb-cables .product-element-top img{object-fit:contain!important;width:100%!important;height:100%!important;transition:.35s ease!important}
body.term-usb-cables .product-grid-item:hover .product-element-top img{transform:scale(1.05)!important}
body.term-usb-cables .wd-entities-title{font-family:'Inter',sans-serif!important;font-size:15px!important;font-weight:600!important;line-height:1.35!important;text-align:center!important;display:-webkit-box!important;-webkit-line-clamp:2!important;-webkit-box-orient:vertical!important;overflow:hidden!important;min-height:40px!important}
body.term-usb-cables .price{font-family:'Space Grotesk',sans-serif!important;font-size:20px!important;font-weight:700!important;color:#7B6CF6!important;text-align:center!important;justify-content:center!important}
body.term-usb-cables .button.add_to_cart_button{background:#0C0C16!important;color:#fff!important;border-radius:100px!important;padding:11px 28px!important;font-weight:600!important;font-size:14px!important;font-family:'Inter',sans-serif!important;border:none!important;margin-top:10px!important;min-width:0!important;min-height:0!important}
body.term-usb-cables .button.add_to_cart_button:hover{background:#7B6CF6!important}
@media(max-width:900px){body.term-usb-cables .wd-products{grid-template-columns:repeat(2,1fr)!important}}
<?php
echo '</style>';
}
add_action('wp_head','mro_usb_cables_css');
function mro_usb_cables_before(){
if(!function_exists('is_product_category')||!is_product_category('usb-cables'))return;
?>
<section class="mro-hero"><div class="mro-hero-in"><div><span class="mro-eyebrow"><span class="mro-pulse"></span> USB Cables Collection</span><h1>Connect anything.<br><em class="mro-grad">Charge everything.</em></h1><p class="mro-lede">Thunderbolt 5 cables built for the fastest connection possible &mdash; 240W charging, 120Gbps data transfer and 8K display output.</p><div class="mro-ctas"><a class="mro-btn-p" href="/product/thunderbolt-5-cable-3ft-120gbps-16k-8k/">Shop USB-C Cables &rarr;</a></div><ul class="mro-trust"><li>240W fast charge</li><li>120Gbps data</li><li>12-month warranty</li></ul></div><div class="mro-visual"><div class="mro-glow"></div><div class="mro-float"><span class="mro-fbadge">240W &middot; <b>120Gbps</b></span><img src="https://mrocioa.com/wp-content/uploads/2026/03/1-600x600.png" alt="MROCIOA USB-C Cable"></div></div></div></section>
<div class="mro-stats-bar"><div class="mro-stats-in"><div class="mro-stat"><div class="n">240W</div><div class="l">Fast charging</div></div><div class="mro-stat"><div class="n">120Gbps</div><div class="l">Data transfer</div></div><div class="mro-stat"><div class="n">4K</div><div class="l">Video output</div></div><div class="mro-stat"><div class="n">4.7&#9733;</div><div class="l">Avg rating</div></div></div></div>
<div class="mro-toolbar"><div class="mro-toolbar-in"><a class="mro-chip on" href="#">All</a><a class="mro-chip" href="#">USB-C to HDMI</a><a class="mro-chip" href="#">USB-C to DP</a><a class="mro-chip" href="#">240W Charge</a><a class="mro-chip" href="#">40Gbps</a></div></div>
<?php
}
add_action('woocommerce_before_main_content','mro_usb_cables_before',5);
function mro_usb_cables_after(){
if(!function_exists('is_product_category')||!is_product_category('usb-cables'))return;
?>
<div class="mro-vals-wrap"><div class="mro-vals"><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg></div><h4>240W fast charging</h4><p>Charge laptops, tablets and phones at full rated power without adapters or converters.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><h4>120Gbps data</h4><p>Thunderbolt 5 bandwidth — transfer a 100GB video in under 7 seconds.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div><h4>12-month warranty</h4><p>Every cable covered for a full year with responsive support.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div><h4>Worldwide shipping</h4><p>Free over $35, multiple warehouses, full tracking.</p></div></div></div>
<div class="mro-cta-band"><h2>Not sure which cable?</h2><p>Tell us your setup and we will match you to the right Thunderbolt 5 or USB-C cable.</p><a href="/contact-us/">Find my cable &rarr;</a></div>
<?php
}
add_action('woocommerce_after_main_content','mro_usb_cables_after',30);



// ============================================================
// MRO SHOP PAGE — Style A v1 | mro_shop
// 策略: template_redirect 完全自渲染
// ============================================================

function mro_shop_render(){
if(!is_shop()) return;

// 获取所有产品
$args=['post_type'=>'product','posts_per_page'=>-1,'post_status'=>'publish',
  'tax_query'=>[['taxonomy'=>'product_cat','field'=>'slug','terms'=>['av-switches','av-cables','usb-cables'],'operator'=>'IN']]];
$all=new WP_Query($args);
$products=[];
while($all->have_posts()){$all->the_post();
  $p=wc_get_product(get_the_ID());
  if(!$p||$p->get_status()!=='publish') continue;
  $products[]=['id'=>get_the_ID(),'name'=>get_the_title(),'url'=>get_permalink(),'img'=>get_the_post_thumbnail_url(get_the_ID(),'medium'),
    'price'=>$p->get_price(),'reg'=>$p->get_regular_price(),'cats'=>wp_get_post_terms(get_the_ID(),'product_cat',['fields'=>'slugs'])];
}
wp_reset_postdata();

$av=get_term_link(82,'product_cat');
$cab=get_term_link(83,'product_cat');
$usb=get_term_link(22,'product_cat');
$shop=get_permalink(wc_get_page_id('shop'));
$contact=home_url('/contact');
$cnt=count($products);

get_header();
echo '<style>
body.woocommerce-shop .wd-content-layout,body.woocommerce-shop .wd-page-content,body.woocommerce-shop #main-content{max-width:100%!important;width:100%!important;padding-left:0!important;padding-right:0!important}
body.woocommerce-shop .wd-content-area{padding:0!important;width:100%!important}
#mro-shop-wrap{font-family:Inter,sans-serif}
#mro-shop-wrap *{box-sizing:border-box}
#mro-shop-wrap .sh{background:radial-gradient(120% 120% at 80% 0%,#1c1840,#06060F 60%);padding:56px 40px 48px;width:100vw;margin-left:calc(-50vw + 50%);box-sizing:border-box;overflow:hidden}
#mro-shop-wrap .sh-row{display:grid;grid-template-columns:1fr 1fr;align-items:center;gap:48px;max-width:1240px;margin:0 auto;padding:80px 40px 70px}
#mro-shop-wrap .sh h1{font-family:"Space Grotesk",sans-serif;font-size:52px;font-weight:700;line-height:1.1;background:linear-gradient(120deg,#fff 45%,#7B6CF6 75%,#37D5F2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:16px}
#mro-shop-wrap .sh .sub{font-size:17px;color:#999;line-height:1.65;max-width:460px;margin-bottom:28px}
#mro-shop-wrap .sh-img{position:relative;display:flex;justify-content:center;align-items:center}
#mro-shop-wrap .sh-float{position:relative;background:#fff;border-radius:20px;padding:28px;box-shadow:0 40px 80px rgba(0,0,0,.5)}
#mro-shop-wrap .sh-float img{width:300px;height:300px;object-fit:contain;display:block}
#mro-shop-wrap .sh-badge{position:absolute;top:-14px;right:-14px;background:#0d1117;border:1px solid #222;color:#37D5F2;font-size:11px;padding:6px 14px;border-radius:20px;font-family:"IBM Plex Mono",monospace;white-space:nowrap}
#mro-shop-wrap .sh-glow{position:absolute;width:380px;height:380px;background:radial-gradient(circle,rgba(123,108,246,.25),transparent 70%);border-radius:50%;pointer-events:none}
#mro-shop-wrap .sh-checks{display:flex;gap:24px;flex-wrap:wrap;margin-top:4px}
#mro-shop-wrap .sh-checks span{font-size:13px;color:#777;display:flex;align-items:center;gap:6px}
#mro-shop-wrap .sh-cats{background:#0A0A0F;padding:28px max(40px,calc((100vw - 1240px) / 2)) 36px;width:100vw;margin-left:calc(-50vw + 50%);display:grid;grid-template-columns:repeat(3,1fr);gap:16px;border-top:1px solid #161622;box-sizing:border-box}
#mro-shop-wrap .sh-cats a{background:#111;border:1px solid #1e1e2e;border-radius:14px;padding:22px;text-decoration:none;display:block;transition:border-color .2s}
#mro-shop-wrap .sh-cats a:hover{border-color:rgba(123,108,246,.5)}
#mro-shop-wrap .sh-cats .ci{margin-bottom:10px;display:flex;align-items:center;height:32px}
#mro-shop-wrap .sh-cats .cn{font-family:"Space Grotesk",sans-serif;font-size:17px;font-weight:600;color:#fff;margin-bottom:4px;display:flex;align-items:center;justify-content:space-between}
#mro-shop-wrap .sh-cats .cn span{color:#333}
#mro-shop-wrap .sh-cats .cm{font-family:"IBM Plex Mono",monospace;font-size:11px;color:#555;line-height:1.6}
#mro-shop-wrap .sh-cats .cc{font-family:"IBM Plex Mono",monospace;font-size:8px;color:#7B6CF6;margin-top:6px;letter-spacing:.06em}
#mro-shop-wrap .ticker{background:#111118;padding:44px max(40px,calc((100vw - 1240px) / 2));width:100vw;margin-left:calc(-50vw + 50%);display:grid;grid-template-columns:repeat(4,1fr);border-top:1px solid #1a1a2a;border-bottom:1px solid #1a1a2a;box-sizing:border-box}
#mro-shop-wrap .ticker .ti{display:flex;flex-direction:column;align-items:center;text-align:center;padding:0 24px;border-right:1px solid #1e1e2e}
#mro-shop-wrap .ticker .ti:first-child{padding-left:0}
#mro-shop-wrap .ticker .ti:last-child{border-right:none}
@media(max-width:640px){#mro-shop-wrap .ticker{grid-template-columns:repeat(2,1fr)!important;padding:28px 20px}#mro-shop-wrap .ticker .ti:nth-child(2){border-right:none}#mro-shop-wrap .ticker .ti:nth-child(3){border-right:1px solid #1e1e2e}}
#mro-shop-wrap .ticker .td{display:none}
#mro-shop-wrap .ticker .tb{font-size:34px;font-weight:700;background:linear-gradient(95deg,#7B6CF6,#37D5F2);-webkit-background-clip:text;background-clip:text;color:transparent;font-family:"Space Grotesk",sans-serif;line-height:1;margin-bottom:8px}
#mro-shop-wrap .ticker .tm{font-size:13px;color:#9a9ab0;font-family:"IBM Plex Mono",monospace;letter-spacing:.04em}
#mro-shop-wrap .toolbar{background:#fff;padding:14px max(40px,calc((100vw - 1240px) / 2));width:100vw;margin-left:calc(-50vw + 50%);display:flex;align-items:center;gap:8px;flex-wrap:wrap;border-bottom:1px solid #e8e8ec;box-sizing:border-box}
#mro-shop-wrap .toolbar a,#mro-shop-wrap .toolbar .tc{font-family:"IBM Plex Mono",monospace;font-size:10px;letter-spacing:.04em;padding:5px 13px;border-radius:14px;border:1px solid #e8e8ec;color:#555568;background:#fff;text-decoration:none;display:inline-block;cursor:pointer}
#mro-shop-wrap .toolbar a.on{background:#7B6CF6;border-color:#7B6CF6;color:#fff}
#mro-shop-wrap .toolbar .gap{flex:1}
body.tax-product_cat ul.wd-products{padding-bottom:48px!important}
#mro-shop-wrap ul.products{background:#F6F6FB;padding:24px max(40px,calc((100vw - 1240px) / 2));width:100vw;margin-left:calc(-50vw + 50%);box-sizing:border-box;--wd-col:4!important;display:grid!important;grid-template-columns:repeat(4,1fr)!important;gap:20px!important;list-style:none!important;padding-bottom:48px!important}
#mro-shop-wrap ul.products li.product{margin:0!important}
@media(max-width:768px){#mro-shop-wrap ul.products{grid-template-columns:repeat(2,1fr)!important;gap:12px!important;padding-left:16px!important;padding-right:16px!important}#mro-shop-wrap .wd-products[class*="grid-columns"]{grid-template-columns:repeat(2,1fr)!important}#mro-shop-wrap .product-image-link{width:100%!important;height:auto!important;aspect-ratio:1/1!important}#mro-shop-wrap .product-image-link img{width:100%!important;height:100%!important;object-fit:contain!important}}
#mro-shop-wrap .wd-entities-title{display:-webkit-box!important;-webkit-line-clamp:2!important;-webkit-box-orient:vertical!important;overflow:hidden!important;text-align:center!important;font-size:15px!important}
#mro-shop-wrap .product-image-link{overflow:hidden!important;width:254px!important;height:254px!important;display:block!important;border-radius:14px!important}
#mro-shop-wrap .product-image-link img{width:254px!important;height:254px!important;object-fit:contain!important;display:block!important}
#mro-shop-wrap .hover-img{display:none!important}
#mro-shop-wrap .product-element-bottom{text-align:center!important}
#mro-shop-wrap .product-wrapper{text-align:center!important}
#mro-shop-wrap .wd-add-btn{display:flex!important;justify-content:center!important}
#mro-shop-wrap .button.add_to_cart_button{width:auto!important;min-height:58px!important;font-size:14px!important;padding:11px 28px!important;border-radius:100px!important;display:inline-flex!important;align-items:center!important;justify-content:center!important}
#mro-shop-wrap .promo{background:#0A0A0F;padding:36px 40px;width:100vw;margin-left:calc(-50vw + 50%);display:grid;grid-template-columns:1fr 1fr;gap:20px;box-sizing:border-box;border-top:1px solid #111}
#mro-shop-wrap .promo a{background:#111;border:1px solid #1e1e2e;border-radius:12px;padding:18px;display:flex;align-items:center;gap:14px;text-decoration:none;transition:border-color .2s}
#mro-shop-wrap .promo a:hover{border-color:rgba(123,108,246,.4)}
#mro-shop-wrap .promo .pi{font-size:28px;flex-shrink:0}
#mro-shop-wrap .promo .pl{font-family:"IBM Plex Mono",monospace;font-size:8px;letter-spacing:.1em;color:#7B6CF6;margin-bottom:4px}
#mro-shop-wrap .promo .pt{font-family:"Space Grotesk",sans-serif;font-size:14px;font-weight:600;color:#fff;margin-bottom:3px}
#mro-shop-wrap .promo .ps2{font-size:11px;color:#666;line-height:1.4}
#mro-shop-wrap .promo .pa{margin-left:auto;color:#333;font-size:16px}
#mro-shop-wrap .trust{background:#fff;padding:52px 40px;width:100vw;margin-left:calc(-50vw + 50%);display:grid;grid-template-columns:repeat(4,1fr);border-top:1px solid #e8e8ec;box-sizing:border-box}
#mro-shop-wrap .trust .ti2{text-align:center;padding:8px;border-right:1px solid #e8e8ec}
#mro-shop-wrap .trust .ti2:last-child{border-right:none}
#mro-shop-wrap .trust .tic{font-size:20px;color:#7B6CF6;margin-bottom:4px}
#mro-shop-wrap .trust .tit{font-size:13px;font-weight:600;color:#ddd;margin-bottom:2px}
#mro-shop-wrap .trust .tis{font-family:"IBM Plex Mono",monospace;font-size:9px;color:#555}
#mro-shop-wrap .cta{background:linear-gradient(135deg,#7B6CF6 0%,#37D5F2 100%);padding:80px 40px;width:100vw;margin-left:calc(-50vw + 50%);text-align:center;box-sizing:border-box}
#mro-shop-wrap .cta h2{font-family:"Space Grotesk",sans-serif;font-size:40px;font-weight:700;color:#fff;margin-bottom:14px}
#mro-shop-wrap .cta p{font-size:17px;color:rgba(255,255,255,.85);margin-bottom:28px}
#mro-shop-wrap .cta a{display:inline-block;background:#0A0A0F;color:#fff;font-family:"Space Grotesk",sans-serif;font-size:13px;font-weight:600;padding:12px 28px;border-radius:50px;text-decoration:none}
@media(max-width:900px){
#mro-shop-wrap .sh-row{grid-template-columns:1fr;padding:48px 28px 40px;text-align:center;gap:24px}
#mro-shop-wrap .sh h1{font-size:36px}
#mro-shop-wrap .sh .sub{font-size:15px;max-width:100%}
#mro-shop-wrap .sh-img{order:-1}
#mro-shop-wrap .sh-float img{width:200px;height:200px}
#mro-shop-wrap .sh-checks{justify-content:center}
#mro-shop-wrap .sh-cats{grid-template-columns:1fr;padding:20px 24px}
.ticker{grid-template-columns:repeat(2,1fr);padding:32px 24px}
#mro-shop-wrap .ticker .ti{padding:16px 12px;border-right:none;border-bottom:1px solid #1e1e2e}
#mro-shop-wrap .ticker .ti:nth-child(odd){border-right:1px solid #1e1e2e}
#mro-shop-wrap .ticker .ti:nth-last-child(-n+2){border-bottom:none}
#mro-shop-wrap .ticker .tb{font-size:28px}
.toolbar{padding:12px 20px}
.grid{grid-template-columns:repeat(2,1fr);padding:16px 20px;gap:12px}
.promo{grid-template-columns:1fr;padding:24px 20px}
.trust{grid-template-columns:repeat(2,1fr);padding:36px 20px}
.cta{padding:56px 24px}
#mro-shop-wrap .cta h2{font-size:28px}
#mro-shop-wrap .cta p{font-size:15px}
}
@media(max-width:480px){
#mro-shop-wrap .sh h1{font-size:28px}
#mro-shop-wrap .sh-float img{width:160px;height:160px}
.ticker{grid-template-columns:1fr}
#mro-shop-wrap .ticker .ti{border-right:none!important;border-bottom:1px solid #1e1e2e}
.grid{grid-template-columns:1fr;padding:12px 16px}
.trust{grid-template-columns:1fr;padding:24px 16px}
}
</style>';

echo '<div id="mro-shop-wrap">';
echo '<div class="sh"><div class="sh-row"><div><span style="font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#37D5F2;font-family:IBM Plex Mono,monospace;display:block;margin-bottom:16px">&#9679; ALL PRODUCTS</span><h1>All our gear.<br><em class="mro-grad">One store.</em></h1><p class="sub">HDMI switches, cables, adapters and splitters &#8212; everything you need to connect your screens, consoles and workspaces.</p><div class="sh-checks"><span>48Gbps full bandwidth</span><span>Free worldwide shipping</span><span>12-month warranty</span></div></div><div class="sh-img"><div class="sh-glow"></div><div class="sh-float"><span class="sh-badge">8K@60Hz &middot; 48Gbps</span><img src="https://mrocioa.com/wp-content/uploads/2025/08/mrocioa-8k-hdmi-2.1-switch-521arc-600x600.jpg" alt="S5 Pro" loading="lazy"></div></div></div></div>';
echo '<div class="sh-cats"><a href="'.$av.'"><div class="ci"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#37D5F2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3L4 7l4 4M4 7h16M16 21l4-4-4-4M20 17H4"/></svg></div><div class="cn">AV Switches <span>&#8594;</span></div><div class="cm">HDMI 2.1 &#183; 8K@60Hz &#183; 4K@120Hz<br>eARC &#183; EDID &#183; CEC</div><div class="cc">9 PRODUCTS &#183; FROM $19.99</div></a><a href="'.$cab.'"><div class="ci"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#37D5F2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="8" width="20" height="8" rx="2"/><path d="M7 8V6h10v2M7 16v2h10v-2"/></svg></div><div class="cn">AV Cables <span>&#8594;</span></div><div class="cm">HDMI 2.1 &#183; DisplayPort<br>48Gbps &#183; eARC certified</div><div class="cc">4 PRODUCTS &#183; FROM $11.99</div></a><a href="'.$usb.'"><div class="ci"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#37D5F2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div><div class="cn">USB Cables <span>&#8594;</span></div><div class="cm">USB-C to HDMI / DP<br>Thunderbolt 5 &#183; 120Gbps</div><div class="cc">5 PRODUCTS &#183; FROM $13.99</div></a></div>';
echo '<div class="ticker"><div class="ti"><div class="td"></div><span class="tb">'.$cnt.' products</span><span class="tm">in stock</span></div><div class="ti"><div class="td"></div><span class="tb">12-month</span><span class="tm">warranty</span></div><div class="ti"><div class="td"></div><span class="tb">90-day</span><span class="tm">free returns</span></div><div class="ti"><div class="td"></div><span class="tb">Support</span><span class="tm">within 24h</span></div></div>';
echo '<div class="toolbar"><a href="'.$shop.'" class="on">All '.$cnt.'</a><a href="'.$av.'">AV Switches</a><a href="'.$cab.'">AV Cables</a><a href="'.$usb.'">USB Cables</a><div class="gap"></div></div>';

wc_setup_loop(array('columns'=>4,'name'=>'shop'));
echo '<ul class="products wd-products wd-grid-g grid-columns-4 elements-grid">';
foreach($products as $pr){$GLOBALS['post']=get_post($pr['id']);setup_postdata($GLOBALS['post']);wc_get_template_part('content','product');}
echo '</ul>';
wp_reset_postdata();
echo '<div class="promo"><a href="'.$av.'"><div class="pi">&#127918;</div><div><div class="pl">GAMING SETUP</div><div class="pt">PS5 &amp; Xbox Ready</div><div class="ps2">4K@120Hz &#183; VRR &#183; ALLM &#183; zero lag</div></div><div class="pa">&#8594;</div></a><a href="'.$usb.'"><div class="pi">&#9889;</div><div><div class="pl">THUNDERBOLT 5</div><div class="pt">120Gbps USB-C Cable</div><div class="ps2">8K display &#183; high-speed data</div></div><div class="pa">&#8594;</div></a></div>';
echo '<div class="trust"><div class="ti2"><div class="tic">&#128737;</div><div class="tit">12-Month Warranty</div><div class="tis">All products</div></div><div class="ti2"><div class="tic">&#128666;</div><div class="tit">Free Shipping</div><div class="tis">Orders $35+</div></div><div class="ti2"><div class="tic">&#8617;</div><div class="tit">90-Day Returns</div><div class="tis">Hassle-free</div></div><div class="ti2"><div class="tic">&#127911;</div><div class="tit">AV Engineer Support</div><div class="tis">Reply within 24h</div></div></div>';
echo '<div class="cta"><h2>Not sure which to pick?</h2><p>Ask our AV engineers &#8212; we help you find the exact cable or switch for your setup.</p><a href="'.$contact.'">Talk to an Engineer</a></div>';
echo '</div>';

get_footer();
exit;
}
add_action('wp_footer',function(){if(is_product_category()||is_shop()){echo'<style>body ul.products.wd-products{padding-bottom:48px!important}body ul.wd-products{padding-bottom:48px!important}</style>';}},1);
// DISABLED: mro_shop_render — shop now uses woocommerce hooks like AV Switch
// add_action('template_redirect','mro_shop_render',1);


// ============================================================
// MRO TECH FORUM PAGE — Style A v1 | mro_tech_forum
// Page ID: 9500 | template_redirect 完全自渲染
// ============================================================

function mro_tech_forum_render(){
if(!is_page(9500)) return;

// 查询所有 posts
$posts_q=new WP_Query(['post_type'=>'post','posts_per_page'=>-1,'post_status'=>'publish','orderby'=>'date','order'=>'DESC']);
$posts=[];
while($posts_q->have_posts()){
  $posts_q->the_post();
  $img=get_the_post_thumbnail_url(get_the_ID(),'medium');
  $posts[]=['id'=>get_the_ID(),'title'=>get_the_title(),'date'=>get_the_date('M j, Y'),'excerpt'=>wp_trim_words(get_the_excerpt(),18,'...'),'url'=>get_permalink(),'img'=>$img];
}
wp_reset_postdata();
$cnt=count($posts);
$featured=!empty($posts)?$posts[0]:null;
$rest=array_slice($posts,1);
$home=home_url('/');

get_header();
echo '<style>
#mro-forum-wrap{font-family:Inter,sans-serif}
body.page-id-9500 #main-content{padding-left:0!important;padding-right:0!important;overflow:visible!important}
#mro-forum-wrap *{box-sizing:border-box;margin:0;padding:0}
#mro-forum-wrap .fh{background:radial-gradient(120% 120% at 80% 0%,#1c1840,#0C0C16 55%);padding:84px max(40px,calc((100vw - 1240px)/2)) 70px;position:relative;overflow:hidden;width:100vw;margin-left:calc(-50vw + 50%);box-sizing:border-box}
#mro-forum-wrap .fh::before{content:"";position:absolute;inset:0;background:radial-gradient(600px 400px at 90% 20%,rgba(55,213,242,.18),transparent 60%),radial-gradient(500px 400px at 10% 80%,rgba(123,108,246,.16),transparent 60%);pointer-events:none;z-index:0;width:100%;height:100%}
#mro-forum-wrap .fh .bc::before{content:"";width:7px;height:7px;border-radius:50%;background:#37D5F2;flex-shrink:0;animation:mrotfpuls 2s infinite;display:block}
#mro-forum-wrap .fh .bc::after{content:"TECH FORUM";font-family:"IBM Plex Mono",monospace;font-size:11px;letter-spacing:.16em;color:#37D5F2;margin-left:8px}
#mro-forum-wrap .fh h1::after{content:".";background:linear-gradient(95deg,#7B6CF6,#37D5F2);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent}
@keyframes mrotfpuls{0%{box-shadow:0 0 0 0 rgba(55,213,242,.6)}70%{box-shadow:0 0 0 9px rgba(55,213,242,0)}100%{box-shadow:0 0 0 0 rgba(55,213,242,0)}}
#mro-forum-wrap .fh .bc{display:inline-flex;align-items:center;gap:0;font-size:0;border:1px solid rgba(55,213,242,.35);border-radius:50px;padding:6px 16px 6px 10px;background:rgba(55,213,242,.06);margin-bottom:32px;position:relative;z-index:1}
#mro-forum-wrap .fh .bc a{display:none}
#mro-forum-wrap .fh h1{font-family:"Space Grotesk",sans-serif;font-size:clamp(2.8rem,5.8vw,4.8rem);font-weight:700;line-height:1.1;letter-spacing:-.02em;margin-bottom:20px;position:relative;z-index:1;background:linear-gradient(120deg,#fff 45%,#7B6CF6 75%,#37D5F2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
#mro-forum-wrap .fh .sub{font-size:18px;color:#c7c7da;line-height:1.7;max-width:520px;margin-bottom:36px;position:relative;z-index:1;font-weight:400}
#mro-forum-wrap .fh .tabs{display:flex;gap:7px;flex-wrap:wrap;padding:20px 0 0;border-top:1px solid rgba(255,255,255,.1);position:relative;z-index:1}
#mro-forum-wrap .fh .tab{font-family:"IBM Plex Mono",monospace;font-size:9px;letter-spacing:.05em;padding:7px 16px;border:1px solid rgba(255,255,255,.12);border-radius:20px;cursor:pointer;background:transparent;color:#9898aa;transition:all .2s}
#mro-forum-wrap .fh .tab.on{background:#7B6CF6;border-color:#7B6CF6;color:#fff}
#mro-forum-wrap .ticker{background:#0B0E14;padding:0 max(40px,calc((100vw - 1240px)/2));display:flex;border-top:1px solid #1a1a2a;border-bottom:1px solid #1a1a2a;width:100vw;margin-left:calc(-50vw + 50%);box-sizing:border-box}
#mro-forum-wrap .ticker .ti{display:flex;flex-direction:column;align-items:flex-start;gap:6px;padding:32px 40px;border-right:1px solid #1a1a2a;white-space:nowrap}
#mro-forum-wrap .ticker .ti:first-child{padding-left:0!important}
#mro-forum-wrap .ticker .ti:last-child{border-right:none}
#mro-forum-wrap .ticker .td{display:none}
#mro-forum-wrap .ticker .tb{font-size:clamp(1.8rem,3vw,2.6rem);font-weight:700;font-family:"IBM Plex Mono",monospace;line-height:1;background:linear-gradient(95deg,#7B6CF6,#37D5F2);-webkit-background-clip:text;background-clip:text;color:transparent}
#mro-forum-wrap .ticker .tm{font-size:11px;color:#555;font-family:"IBM Plex Mono",monospace;letter-spacing:.06em;line-height:1}
#mro-forum-wrap .featured{background:#F6F6FB;padding:60px max(40px,calc((100vw - 1240px)/2));border-bottom:1px solid #e8e8ec;width:100vw;margin-left:calc(-50vw + 50%);box-sizing:border-box}
#mro-forum-wrap .feat-label{font-family:"IBM Plex Mono",monospace;font-size:9px;letter-spacing:.08em;color:#7B6CF6;border:1px solid rgba(123,108,246,.25);border-radius:4px;padding:3px 8px;display:inline-flex;align-items:center;gap:5px;margin-bottom:24px}
#mro-forum-wrap .feat-inner{display:grid;grid-template-columns:1fr 1fr;gap:36px;align-items:start}
#mro-forum-wrap .feat-img{background:linear-gradient(135deg,#1a1a2a 0%,#0f0f18 100%);border-radius:12px;aspect-ratio:16/9;overflow:hidden;position:relative;display:flex;align-items:center;justify-content:center;min-height:280px}
#mro-forum-wrap .feat-img img{width:100%;height:100%;object-fit:cover}
#mro-forum-wrap .feat-img .no-img{font-size:48px;opacity:.25}
#mro-forum-wrap .feat-img .badge{position:absolute;top:12px;left:12px;font-family:"IBM Plex Mono",monospace;font-size:8px;letter-spacing:.08em;background:#7B6CF6;color:#fff;padding:4px 10px;border-radius:4px}
#mro-forum-wrap .feat-body .meta{font-family:"IBM Plex Mono",monospace;font-size:9px;color:#888;letter-spacing:.06em;margin-bottom:14px;display:flex;align-items:center;gap:10px}
#mro-forum-wrap .feat-body .cat{font-family:"IBM Plex Mono",monospace;font-size:8px;background:rgba(123,108,246,.12);color:#9898cc;padding:3px 8px;border-radius:4px;border:1px solid rgba(123,108,246,.2)}
#mro-forum-wrap .feat-body h2{font-family:"Space Grotesk",sans-serif;font-size:clamp(1.5rem,2.6vw,2.2rem);font-weight:700;color:#111118;line-height:1.15;margin-bottom:16px;letter-spacing:-.01em}
#mro-forum-wrap .feat-body p{font-size:14px;color:#555568;line-height:1.65;margin-bottom:22px}
#mro-forum-wrap .feat-body .rlink{font-family:"IBM Plex Mono",monospace;font-size:10px;color:#7B6CF6;text-decoration:none}
#mro-forum-wrap .grid-hd{padding:28px max(40px,calc((100vw - 1240px)/2)) 20px;display:flex;align-items:center;justify-content:space-between;background:#F6F6FB;border-bottom:1px solid #e8e8ec;width:100vw;margin-left:calc(-50vw + 50%);box-sizing:border-box}
#mro-forum-wrap .grid-hd .gt{font-family:"Space Grotesk",sans-serif;font-size:18px;font-weight:700;color:#111118}
#mro-forum-wrap .grid-hd .gc{font-family:"IBM Plex Mono",monospace;font-size:9px;color:#9898aa}
#mro-forum-wrap .grid{background:#F6F6FB;padding:28px max(40px,calc((100vw - 1240px)/2)) 52px;display:grid;grid-template-columns:repeat(3,1fr);gap:20px;width:100vw;margin-left:calc(-50vw + 50%);box-sizing:border-box}
#mro-forum-wrap .card{background:#fff;border:1px solid #e8e8ec;border-radius:14px;overflow:hidden;cursor:pointer;text-decoration:none;display:block;transition:border-color .2s,box-shadow .2s}
#mro-forum-wrap .card:hover{border-color:#c0bef0;box-shadow:0 4px 16px rgba(123,108,246,.12)}
#mro-forum-wrap .card .cimg{background:#f0f0f5;aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative}
#mro-forum-wrap .card .cimg img{width:100%;height:100%;object-fit:cover}
#mro-forum-wrap .card .cimg .cpill{position:absolute;top:8px;left:8px;font-family:"IBM Plex Mono",monospace;font-size:7px;letter-spacing:.06em;padding:3px 8px;border-radius:3px;background:#7B6CF6;color:#fff}
#mro-forum-wrap .card .cbody{padding:18px}
#mro-forum-wrap .card .cdate{font-family:"IBM Plex Mono",monospace;font-size:8px;color:#555568;letter-spacing:.06em;margin-bottom:6px}
#mro-forum-wrap .card .ctitle{font-family:"Space Grotesk",sans-serif;font-size:15px;font-weight:600;color:#111118;line-height:1.35;margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
#mro-forum-wrap .card .cexc{font-size:11px;color:#555568;line-height:1.5;margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
#mro-forum-wrap .card .clink{font-family:"IBM Plex Mono",monospace;font-size:9px;color:#7B6CF6}
#mro-forum-wrap .guides{background:#0B0E14;padding:48px max(40px,calc((100vw - 1240px)/2));border-top:1px solid #1a1a2a;width:100vw;margin-left:calc(-50vw + 50%);box-sizing:border-box}
#mro-forum-wrap .guides .ghd{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
#mro-forum-wrap .guides .gt2{font-family:"Space Grotesk",sans-serif;font-size:15px;font-weight:600;color:#fff}
#mro-forum-wrap .guides .glink{font-family:"IBM Plex Mono",monospace;font-size:9px;color:#7B6CF6;text-decoration:none}
#mro-forum-wrap .guides .glist{display:flex;flex-direction:column;gap:8px}
#mro-forum-wrap .guides .grow{display:flex;align-items:center;gap:14px;padding:20px 20px;background:#111118;border:1px solid #1e1e2e;border-radius:10px;text-decoration:none;transition:border-color .2s}
#mro-forum-wrap .guides .grow:hover{border-color:rgba(123,108,246,.4)}
#mro-forum-wrap .guides .gnum{font-family:"IBM Plex Mono",monospace;font-size:11px;color:#555;width:22px;flex-shrink:0}
#mro-forum-wrap .guides .gicon{font-size:18px;flex-shrink:0}
#mro-forum-wrap .guides .gbody{flex:1}
#mro-forum-wrap .guides .gcat{font-family:"IBM Plex Mono",monospace;font-size:7px;letter-spacing:.08em;color:#37D5F2;margin-bottom:3px}
#mro-forum-wrap .guides .gtitle{font-family:"Space Grotesk",sans-serif;font-size:15px;font-weight:600;color:#fff;line-height:1.3}
#mro-forum-wrap .guides .garrow{color:#444;font-size:12px;flex-shrink:0}
#mro-forum-wrap .nl{background:linear-gradient(135deg,#7B6CF6 0%,#37D5F2 100%);padding:96px max(40px,calc((100vw - 1240px)/2));display:flex;align-items:center;justify-content:space-between;gap:32px;min-height:280px;width:100vw;margin-left:calc(-50vw + 50%);box-sizing:border-box}
#mro-forum-wrap .nl h2{font-family:"Space Grotesk",sans-serif;font-size:clamp(1.6rem,2.8vw,2.4rem);font-weight:700;color:#fff;margin-bottom:8px;letter-spacing:-.01em}
#mro-forum-wrap .nl p{font-size:16px;color:rgba(255,255,255,.85)}
#mro-forum-wrap .nl-form{display:flex;gap:10px;flex-shrink:0}
#mro-forum-wrap .nl-form input{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);border-radius:8px;padding:14px 20px;font-size:13px;color:#fff;outline:none;min-width:240px;font-family:Inter,sans-serif}
#mro-forum-wrap .nl-form input::placeholder{color:rgba(255,255,255,.5)}
#mro-forum-wrap .nl-form button{background:#0A0A0F;color:#fff;border:none;border-radius:8px;font-family:"Space Grotesk",sans-serif;font-size:13px;font-weight:600;padding:14px 28px;cursor:pointer;letter-spacing:.04em;white-space:nowrap}
#mro-forum-wrap .trust{background:#111118;padding:28px max(40px,calc((100vw - 1240px)/2));display:grid;grid-template-columns:repeat(4,1fr);border-top:1px solid #1a1a2a;width:100vw;margin-left:calc(-50vw + 50%);box-sizing:border-box}
#mro-forum-wrap .trust .trs{text-align:center;padding:12px;border-right:1px solid #1a1a2a}
#mro-forum-wrap .trust .trs:last-child{border-right:none}
#mro-forum-wrap .trust .tric{font-size:26px;color:#7B6CF6;margin-bottom:8px}
#mro-forum-wrap .trust .trt{font-size:13px;font-weight:600;color:#ddd;margin-bottom:4px;font-family:Inter,sans-serif}
#mro-forum-wrap .trust .trs2{font-family:"IBM Plex Mono",monospace;font-size:8px;color:#555}
@media(max-width:767px){#mro-forum-wrap{overflow-x:hidden}#mro-forum-wrap .fh{padding:52px 20px 44px}#mro-forum-wrap .ticker{display:grid;grid-template-columns:repeat(2,1fr);padding:0}#mro-forum-wrap .ticker .ti{padding:20px 16px;white-space:normal;border-right:none;border-bottom:1px solid #1a1a2a}#mro-forum-wrap .ticker .ti:first-child{padding-left:16px!important}#mro-forum-wrap .ticker .ti:nth-child(odd){border-right:1px solid #1a1a2a}#mro-forum-wrap .ticker .ti:nth-last-child(-n+2){border-bottom:none}#mro-forum-wrap .feat-inner{grid-template-columns:1fr}#mro-forum-wrap .feat-img{min-height:auto}#mro-forum-wrap .grid{grid-template-columns:1fr;padding:24px 16px 40px;gap:16px}#mro-forum-wrap .nl{flex-direction:column;padding:52px 20px;gap:20px;min-height:auto;text-align:center}#mro-forum-wrap .nl .nl-form{width:100%;flex-wrap:wrap}#mro-forum-wrap .nl .nl-form button{flex:1 0 100%;border-radius:10px}#mro-forum-wrap .trust{grid-template-columns:repeat(2,1fr)}#mro-forum-wrap .feat-img{min-height:auto}#mro-forum-wrap .featured{padding:32px 16px}}
</style>';

echo '<div id="mro-forum-wrap">';

// HERO
echo '<div class="fh"><nav class="bc"><a href="'.$home.'">Home</a> / Tech Forum</nav><h1>Tech Forum</h1><p class="sub">Setup guides, first impressions and deep dives &#8212; written by AV engineers for real home theater builders.</p><div class="tabs"><a class="tab on" href="#">All</a><a class="tab" href="#">Setup Guides</a><a class="tab" href="#">First Impressions</a><a class="tab" href="#">Gaming</a><a class="tab" href="#">HDMI 2.1</a><a class="tab" href="#">Troubleshooting</a></div></div>';

// TICKER
echo '<div class="ticker"><div class="ti"><div class="td"></div><span class="tb">'.$cnt.' articles</span><span class="tm">published</span></div><div class="ti"><div class="td"></div><span class="tb">AV engineers</span><span class="tm">writing</span></div><div class="ti"><div class="td"></div><span class="tb">Setup guides</span><span class="tm">step-by-step</span></div><div class="ti"><div class="td"></div><span class="tb">Free</span><span class="tm">no account needed</span></div></div>';

// FEATURED POST
if($featured){
  $fimg=$featured['img']?'<img src="'.esc_url($featured['img']).'" alt="'.esc_attr($featured['title']).'">':'<span class="no-img">&#128187;</span>';
  echo '<div class="featured"><div class="feat-label">&#128204; Featured</div><div class="feat-inner"><div class="feat-img">'.$fimg.'<span class="badge">SETUP GUIDE</span></div><div class="feat-body"><div class="meta"><span class="cat">Troubleshooting</span><span>'.esc_html($featured['date']).'</span></div><h2>'.esc_html($featured['title']).'</h2><p>'.esc_html($featured['excerpt']).'</p><a class="rlink" href="'.esc_url($featured['url']).'">Read full guide &#8594;</a></div></div></div>';
}

// POST GRID
$cat_map=['Playstation'=>'GAMING','Forza'=>'GAMING','Xbox'=>'GAMING','PS5'=>'GAMING','Setup'=>'SETUP GUIDE','Black Screen'=>'TROUBLESHOOTING','HDMI'=>'HDMI 2.1'];
echo '<div class="grid-hd"><span class="gt">All Articles</span><span class="gc">'.$cnt.' POSTS</span></div>';
echo '<div class="grid">';
foreach($rest as $p){
  $cat='TECH';
  foreach($cat_map as $kw=>$cv){ if(stripos($p['title'],$kw)!==false){$cat=$cv;break;} }
  $bg_styles=['GAMING'=>'background:linear-gradient(135deg,#1a0f0f,#2a1010)','SETUP GUIDE'=>'background:linear-gradient(135deg,#0f1a0f,#102a10)','TROUBLESHOOTING'=>'background:linear-gradient(135deg,#1a1a0f,#2a2a10)','HDMI 2.1'=>'background:linear-gradient(135deg,#0f0f1a,#10102a)'];
  $bg=isset($bg_styles[$cat])?$bg_styles[$cat]:'background:#f5f5f7';
  $emoji_map=['GAMING'=>'&#127918;','SETUP GUIDE'=>'&#128295;','TROUBLESHOOTING'=>'&#9888;&#65039;','HDMI 2.1'=>'&#128268;','TECH'=>'&#128187;'];
  $emoji=isset($emoji_map[$cat])?$emoji_map[$cat]:'&#128187;';
  $cimg=$p['img']?'<img src="'.esc_url($p['img']).'" alt="'.esc_attr($p['title']).'">':'<span style="font-size:28px">'.$emoji.'</span>';
  echo '<a class="card" href="'.esc_url($p['url']).'"><div class="cimg" style="'.$bg.'">'.$cimg.'<span class="cpill">'.$cat.'</span></div><div class="cbody"><div class="cdate">'.strtoupper($p['date']).'</div><div class="ctitle">'.esc_html($p['title']).'</div><div class="cexc">'.esc_html($p['excerpt']).'</div><span class="clink">Read more &#8594;</span></div></a>';
}
echo '</div>';

// QUICK GUIDES BAND
$guide_url=$featured?esc_url($featured['url']):'#';
echo '<div class="guides"><div class="ghd"><span class="gt2">Quick Setup Guides</span><a class="glink" href="#">View all &#8594;</a></div><div class="glist"><a class="grow" href="'.$guide_url.'"><span class="gnum">01</span><span class="gicon">&#128260;</span><div class="gbody"><div class="gcat">HDMI SWITCH</div><div class="gtitle">How to connect PS5 + Xbox + Apple TV to one display</div></div><span class="garrow">&#8594;</span></a><a class="grow" href="'.$guide_url.'"><span class="gnum">02</span><span class="gicon">&#9899;</span><div class="gbody"><div class="gcat">TROUBLESHOOTING</div><div class="gtitle">Black screen on HDMI switch &#8212; causes and fixes</div></div><span class="garrow">&#8594;</span></a><a class="grow" href="#"><span class="gnum">03</span><span class="gicon">&#128266;</span><div class="gbody"><div class="gcat">eARC</div><div class="gtitle">Getting Dolby Atmos through a switch to your soundbar</div></div><span class="garrow">&#8594;</span></a></div></div>';

// NEWSLETTER
echo '<div class="nl"><div><h2>Get setup guides in your inbox</h2><p>New articles, troubleshooting tips and product news &#8212; no spam.</p></div><div class="nl-form"><input type="email" placeholder="your@email.com"><button type="button">Subscribe</button></div></div>';

// TRUST
echo '<div class="trust"><div class="trs"><div class="tric">&#9997;</div><div class="trt">Written by AV Engineers</div><div class="trs2">Not sponsored</div></div><div class="trs"><div class="tric">&#128295;</div><div class="trt">Setup Guides</div><div class="trs2">Step-by-step</div></div><div class="trs"><div class="tric">&#127358;</div><div class="trt">Always Free</div><div class="trs2">No account needed</div></div><div class="trs"><div class="tric">&#128140;</div><div class="trt">Weekly Updates</div><div class="trs2">New guides every week</div></div></div>';

echo '</div>';
get_footer();
exit;
}
add_action('template_redirect','mro_tech_forum_render',1);




/* ===== MRO_SHARED_CSS : shared hero/stats/toolbar/vals/cta styles for AV Cables + USB Cables ===== */
add_action('wp_head','mro_shared_cat_css',98);
function mro_shared_cat_css(){
if(!function_exists('is_product_category'))return;
if(!is_product_category('av-cables')&&!is_product_category('usb-cables'))return;
echo '<style id="mro-shared">';
?>
.mro-hero{position:relative;overflow:hidden;background:radial-gradient(120% 120% at 80% 0%,#1c1840,#0C0C16 55%);color:#fff;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-hero:before{content:"";position:absolute;inset:0;background:radial-gradient(600px 400px at 90% 20%,rgba(55,213,242,.22),transparent 60%),radial-gradient(520px 400px at 8% 90%,rgba(123,108,246,.30),transparent 60%);pointer-events:none}
.mro-hero-in{position:relative;z-index:1;max-width:1240px;margin:0 auto;padding:84px 28px 70px;display:grid;grid-template-columns:1.05fr .95fr;gap:40px;align-items:center;min-height:540px}
.mro-eyebrow{display:inline-flex;align-items:center;gap:8px;font-family:'IBM Plex Mono',monospace;font-size:13px;letter-spacing:.16em;text-transform:uppercase;color:#37D5F2;border:1px solid rgba(55,213,242,.35);padding:7px 14px;border-radius:100px;margin-bottom:24px}
.mro-pulse{width:7px;height:7px;border-radius:50%;background:#37D5F2;display:inline-block;animation:mropulse 2s infinite}
@keyframes mropulse{0%{box-shadow:0 0 0 0 rgba(55,213,242,.6)}70%{box-shadow:0 0 0 9px rgba(55,213,242,0)}100%{box-shadow:0 0 0 0 rgba(55,213,242,0)}}
.mro-hero h1{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:clamp(2.6rem,5.4vw,4.4rem);line-height:1.1;letter-spacing:-.01em;color:#fff;margin:0 0 14px}
.mro-grad{background:linear-gradient(95deg,#7B6CF6,#37D5F2);-webkit-background-clip:text;background-clip:text;color:transparent;font-style:italic}
.mro-lede{font-size:18px;color:#c7c7da;max-width:480px;margin:0 0 34px}
.mro-ctas{display:flex;gap:14px;flex-wrap:wrap}
.mro-btn-p,.mro-btn-g{display:inline-flex;align-items:center;font-weight:600;font-size:15.5px;padding:15px 28px;border-radius:13px;cursor:pointer;border:none;font-family:'Inter',sans-serif;text-decoration:none;transition:.2s}
.mro-btn-p{background:linear-gradient(95deg,#7B6CF6,#37D5F2);color:#fff;box-shadow:0 14px 34px -10px rgba(123,108,246,.7)}
.mro-btn-g{background:rgba(255,255,255,.06);color:#fff;border:1px solid rgba(255,255,255,.2)}
.mro-trust{display:flex;gap:22px;margin-top:34px;font-size:13.5px;color:#a9a9c2;flex-wrap:wrap;list-style:none;padding:0}
.mro-trust li{display:inline-flex;align-items:center;gap:7px}
.mro-trust li:before{content:"\2713 ";color:#37D5F2;font-weight:700}
.mro-visual{position:relative;display:grid;place-items:center}
.mro-glow{position:absolute;width:380px;height:380px;background:radial-gradient(circle,rgba(123,108,246,.45),transparent 65%);filter:blur(20px)}
.mro-float{position:relative;width:min(100%,420px);background:#fff;border-radius:28px;padding:16px;box-shadow:0 44px 90px -24px rgba(0,0,0,.6);animation:mrofloat 6s ease-in-out infinite}
@keyframes mrofloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-14px)}}
.mro-float img{display:block;width:100%;border-radius:14px}
.mro-fbadge{position:absolute;top:-3%;right:-3%;z-index:2;background:#fff;color:#0C0C16;font-family:'IBM Plex Mono',monospace;font-weight:500;font-size:12px;padding:8px 13px;border-radius:11px;box-shadow:0 12px 30px rgba(0,0,0,.4);transform:rotate(4deg)}
.mro-fbadge b{color:#7B6CF6}
.mro-stats-bar{background:#15151F;border-top:1px solid rgba(255,255,255,.06);width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-stats-in{max-width:1240px;margin:0 auto;padding:30px 28px;display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.mro-stat{text-align:center;color:#fff;padding:6px}
.mro-stat .n{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:34px;background:linear-gradient(95deg,#7B6CF6,#37D5F2);-webkit-background-clip:text;background-clip:text;color:transparent}
.mro-stat .l{font-size:13px;color:#9a9ab0;font-family:'IBM Plex Mono',monospace;letter-spacing:.04em;margin-top:4px}
.mro-toolbar{background:#F6F6FB;border-bottom:1px solid #E7E7F0;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-toolbar-in{max-width:1240px;margin:0 auto;padding:20px 28px;display:flex;align-items:center;gap:14px;flex-wrap:wrap}
.mro-chip{font-size:14px;font-weight:500;padding:9px 18px;border-radius:100px;background:#fff;border:1px solid #E7E7F0;cursor:pointer;color:#44444f;text-decoration:none;transition:.18s}
.mro-chip.on{background:#0C0C16;color:#fff;border-color:#0C0C16}
.mro-vals-wrap{background:#fff;border-top:1px solid #E7E7F0;padding:60px 0;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-vals{max-width:1240px;margin:0 auto;padding:0 28px;display:grid;grid-template-columns:repeat(4,1fr);gap:26px}
.mro-val .ic{width:48px;height:48px;border-radius:13px;background:linear-gradient(135deg,rgba(123,108,246,.12),rgba(55,213,242,.12));display:grid;place-items:center;color:#7B6CF6;margin-bottom:15px}
.mro-val h4{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:16px;margin:0 0 6px;color:#16161F}
.mro-val p{font-size:13.5px;color:#6B6B7B;line-height:1.55;margin:0}
.mro-cta-band{background:linear-gradient(95deg,#7B6CF6,#37D5F2);color:#fff;text-align:center;padding:64px 28px;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-cta-band h2{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.1;margin:0 0 12px;color:#fff}
.mro-cta-band p{color:rgba(255,255,255,.88);max-width:520px;margin:0 auto 26px;font-size:16px}
.mro-cta-band a{display:inline-block;background:#fff;color:#0C0C16;font-weight:600;padding:15px 30px;border-radius:13px;text-decoration:none}
@media(max-width:900px){
.mro-hero-in{grid-template-columns:1fr;padding:54px 28px;min-height:auto;text-align:center;gap:30px}
.mro-visual{order:-1}.mro-float{width:280px}
.mro-lede,.mro-ctas,.mro-trust{justify-content:center}
.mro-stats-in{grid-template-columns:repeat(2,1fr)}
.mro-vals{grid-template-columns:repeat(2,1fr)}
}
<?php
echo '</style>';
}
/* ===== /MRO_SHARED_CSS ===== */


/* ===== MRO_TECH_FORUM : Tech Forum redesign via template_redirect. Reversible: delete this block. ===== */
add_action('template_redirect','mro_tf_take_over',1);
function mro_tf_take_over(){
if(!is_page(9500))return;
get_header();
?>
<style>
:root{--v:#7B6CF6;--c:#37D5F2;--ink:#0C0C16;--ink2:#15151F;--page:#F6F6FB;--line:#E7E7F0;--text:#16161F;--muted:#6B6B7B;--md:#9A9AB0;--grad:linear-gradient(95deg,#7B6CF6,#37D5F2)}
body.page-id-9500{background:var(--page)}
.tf-wrap{max-width:1240px;margin:0 auto;padding:0 28px}
.tf-hero{position:relative;overflow:hidden;background:radial-gradient(120% 120% at 80% 0%,#1c1840,var(--ink) 55%);color:#fff}
.tf-hero:before{content:"";position:absolute;inset:0;background:radial-gradient(600px 360px at 90% 20%,rgba(55,213,242,.2),transparent 60%),radial-gradient(520px 400px at 8% 90%,rgba(123,108,246,.28),transparent 60%)}
.tf-hero-in{position:relative;z-index:1;max-width:1240px;margin:0 auto;padding:80px 28px 64px}
.tf-eyebrow{display:inline-flex;align-items:center;gap:8px;font-family:'IBM Plex Mono',monospace;font-size:12px;letter-spacing:.16em;text-transform:uppercase;color:var(--c);border:1px solid rgba(55,213,242,.35);padding:6px 13px;border-radius:100px;margin-bottom:22px}
.tf-dot{width:7px;height:7px;border-radius:50%;background:var(--c);display:inline-block;animation:mropulse 2s infinite}
.tf-hero h1{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:clamp(2.4rem,5vw,4rem);color:#fff;max-width:680px;margin-bottom:16px;letter-spacing:-.01em;line-height:1.1}
.tf-grad{background:var(--grad);-webkit-background-clip:text;background-clip:text;color:transparent}
.tf-hero p{font-size:18px;color:#c7c7da;max-width:540px;margin-bottom:32px}
.tf-chips{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:32px}
.tf-chip{font-size:13px;padding:7px 16px;border-radius:100px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);color:#d4d4e8;font-family:'IBM Plex Mono',monospace;letter-spacing:.02em}
.tf-chip.on{background:rgba(123,108,246,.25);border-color:rgba(123,108,246,.5);color:#fff}
.tf-trust{display:flex;gap:24px;flex-wrap:wrap}
.tf-trust-item{font-size:13px;color:#9a9ab0;font-family:'IBM Plex Mono',monospace}
.tf-trust-item b{color:var(--c)}
.tf-stats{background:var(--ink2);border-top:1px solid rgba(255,255,255,.06)}
.tf-stats-in{max-width:1240px;margin:0 auto;padding:26px 28px;display:grid;grid-template-columns:repeat(4,1fr);text-align:center}
.tf-stat .n{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:28px;background:var(--grad);-webkit-background-clip:text;background-clip:text;color:transparent}
.tf-stat .l{font-size:12px;color:#9a9ab0;font-family:'IBM Plex Mono',monospace;letter-spacing:.04em;margin-top:3px}
.tf-sec{padding:56px 0}
.tf-label{font-family:'IBM Plex Mono',monospace;font-size:12px;letter-spacing:.14em;text-transform:uppercase;color:var(--v);margin-bottom:24px;display:flex;align-items:center;gap:10px}
.tf-label:after{content:"";flex:1;height:1px;background:var(--line)}
.tf-featured{display:grid;grid-template-columns:1fr 1fr;border-radius:20px;overflow:hidden;box-shadow:0 24px 60px -20px rgba(20,12,60,.2);border:1px solid var(--line);background:#fff}
.tf-feat-img{position:relative;min-height:380px;overflow:hidden;background:#0d0d1a}
.tf-feat-img img{width:100%;height:100%;object-fit:cover;opacity:.8}
.tf-feat-badge{position:absolute;top:20px;left:20px;font-family:'IBM Plex Mono',monospace;font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#fff;background:rgba(239,68,68,.85);padding:5px 12px;border-radius:6px}
.tf-feat-body{padding:44px 48px;display:flex;flex-direction:column;justify-content:center;gap:14px}
.tf-feat-meta{display:flex;align-items:center;gap:10px}
.tf-tag{font-family:'IBM Plex Mono',monospace;font-size:11px;letter-spacing:.06em;text-transform:uppercase;padding:4px 10px;border-radius:6px}
.tf-tag-trouble{background:#FEE2E2;color:#B91C1C}
.tf-tag-gaming{background:#EDE9FE;color:#6D28D9}
.tf-feat-date{font-size:13px;color:var(--muted);font-family:'IBM Plex Mono',monospace}
.tf-feat-body h2{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:clamp(1.3rem,2.2vw,1.8rem);line-height:1.2;color:var(--text)}
.tf-feat-body p{font-size:15px;color:var(--muted);line-height:1.6}
.tf-read{display:inline-flex;align-items:center;gap:7px;font-weight:600;font-size:14px;color:var(--v);text-decoration:none;margin-top:6px}
.tf-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.tf-card{background:#fff;border:1px solid var(--line);border-radius:18px;overflow:hidden;display:flex;flex-direction:column;text-decoration:none;color:inherit;transition:.22s}
.tf-card:hover{transform:translateY(-5px);box-shadow:0 20px 50px -18px rgba(20,12,60,.16);border-color:transparent}
.tf-card-img{aspect-ratio:16/9;overflow:hidden;background:#e8e8f0}
.tf-card-img img{width:100%;height:100%;object-fit:cover;transition:.35s}
.tf-card:hover .tf-card-img img{transform:scale(1.05)}
.tf-card-body{padding:22px;flex:1;display:flex;flex-direction:column;gap:10px}
.tf-card-meta{display:flex;align-items:center;gap:10px}
.tf-card-date{font-size:12px;color:var(--md);font-family:'IBM Plex Mono',monospace}
.tf-card-body h3{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:17px;line-height:1.3;color:var(--text)}
.tf-card-body p{font-size:13.5px;color:var(--muted);line-height:1.55;flex:1}
.tf-guides{background:var(--ink2)}
.tf-guides-in{max-width:1240px;margin:0 auto;padding:52px 28px}
.tf-guides-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:28px}
.tf-guides-head h2{font-family:'Space Grotesk',sans-serif;font-size:1.7rem;color:#fff}
.tf-guide-item{display:grid;grid-template-columns:52px 44px 1fr;align-items:center;gap:16px;padding:18px 22px;border-radius:14px;transition:.18s;text-decoration:none;color:inherit}
.tf-guide-item:hover{background:rgba(255,255,255,.05)}
.tf-guide-num{font-family:'IBM Plex Mono',monospace;font-size:22px;font-weight:500;color:#3a3a52}
.tf-guide-icon{width:40px;height:40px;border-radius:10px;background:rgba(123,108,246,.15);display:grid;place-items:center;font-size:18px}
.tf-guide-cat{font-family:'IBM Plex Mono',monospace;font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--c);margin-bottom:4px}
.tf-guide-title{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:15.5px;color:#e8e8f8}
.tf-guide-div{height:1px;background:rgba(255,255,255,.06);margin:0 22px}
.tf-nl{background:var(--grad);color:#fff;text-align:center;padding:60px 28px}
.tf-nl h2{font-family:'Space Grotesk',sans-serif;font-size:clamp(1.7rem,3vw,2.3rem);margin-bottom:10px;color:#fff}
.tf-nl p{color:rgba(255,255,255,.88);font-size:16px;max-width:460px;margin:0 auto 26px}
.tf-nl-row{display:flex;max-width:460px;margin:0 auto;border-radius:13px;overflow:hidden;box-shadow:0 12px 36px rgba(0,0,0,.22)}
.tf-nl-row input{flex:1;padding:15px 18px;font-size:15px;border:none;outline:none;font-family:'Inter',sans-serif}
.tf-nl-row button{background:var(--ink);color:#fff;border:none;padding:15px 26px;font-weight:700;font-size:13px;cursor:pointer;font-family:'IBM Plex Mono',monospace;letter-spacing:.06em;text-transform:uppercase}
.tf-nl-note{margin-top:12px;font-size:12px;color:rgba(255,255,255,.6);font-family:'IBM Plex Mono',monospace}
.tf-trust-bar{background:#fff;border-top:1px solid var(--line);padding:40px 28px}
.tf-trust-grid{max-width:1240px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:24px;text-align:center}
.tf-trust-cell h4{font-family:'Space Grotesk',sans-serif;font-size:15px;font-weight:600;margin:10px 0 4px;color:var(--text)}
.tf-trust-cell p{font-size:13px;color:var(--muted)}
@media(max-width:900px){.tf-featured{grid-template-columns:1fr}.tf-feat-img{min-height:240px}.tf-grid{grid-template-columns:1fr}.tf-stats-in{grid-template-columns:repeat(2,1fr)}.tf-trust-grid{grid-template-columns:repeat(2,1fr)}}
</style>
<?php
$all=get_posts(['numberposts'=>10,'post_status'=>'publish','orderby'=>'date','order'=>'DESC']);
$featured=$all[0];$rest=array_slice($all,1);
$fimg=get_the_post_thumbnail_url($featured->ID,'large')?:'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=800&auto=format';
$fcats=get_the_category($featured->ID);$fcat=$fcats?$fcats[0]->name:'Article';
?>
<div class="tf-hero">
<div class="tf-hero-in">
<span class="tf-eyebrow"><span class="tf-dot"></span> Tech Forum</span>
<h1>Real answers for<br><em class="tf-grad" style="font-style:normal">real home theater builders.</em></h1>
<p>Setup guides, first impressions and deep dives — written by AV engineers, not content farms.</p>
<div class="tf-chips"><span class="tf-chip on">All</span><span class="tf-chip">Setup Guides</span><span class="tf-chip">First Impressions</span><span class="tf-chip">Gaming</span><span class="tf-chip">HDMI 2.1</span><span class="tf-chip">Troubleshooting</span></div>
<div class="tf-trust"><span class="tf-trust-item"><b><?php echo wp_count_posts()->publish; ?></b> articles</span><span class="tf-trust-item"><b>AV engineers</b> writing</span><span class="tf-trust-item"><b>Setup guides</b> step-by-step</span><span class="tf-trust-item"><b>Free</b> always</span></div>
</div>
<div class="tf-stats"><div class="tf-stats-in">
<div class="tf-stat"><div class="n"><?php echo wp_count_posts()->publish; ?></div><div class="l">Articles</div></div>
<div class="tf-stat"><div class="n">3</div><div class="l">Categories</div></div>
<div class="tf-stat"><div class="n">100%</div><div class="l">Engineer-written</div></div>
<div class="tf-stat"><div class="n">Free</div><div class="l">Always</div></div>
</div></div>
</div>
<section class="tf-sec" style="background:#fff"><div class="tf-wrap">
<div class="tf-label">// Featured</div>
<div class="tf-featured">
<div class="tf-feat-img"><img src="<?php echo esc_attr($fimg); ?>" alt="<?php echo esc_attr($featured->post_title); ?>"><span class="tf-feat-badge">FEATURED</span></div>
<div class="tf-feat-body">
<div class="tf-feat-meta"><span class="tf-tag tf-tag-trouble"><?php echo esc_html($fcat); ?></span><span class="tf-feat-date"><?php echo date('M j, Y',strtotime($featured->post_date)); ?></span></div>
<h2><?php echo esc_html($featured->post_title); ?></h2>
<p><?php echo esc_html(wp_trim_words(strip_tags($featured->post_content),28)); ?></p>
<a class="tf-read" href="<?php echo get_permalink($featured->ID); ?>">Read full guide &rarr;</a>
</div></div>
</div></section>
<?php if(!empty($rest)): ?>
<section class="tf-sec" style="background:var(--page)"><div class="tf-wrap">
<div class="tf-label">// Latest articles</div>
<div class="tf-grid">
<?php foreach($rest as $p):$img=get_the_post_thumbnail_url($p->ID,'medium_large')?:'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=600&auto=format';$cats=get_the_category($p->ID);$cat=$cats?$cats[0]->name:'Article';?>
<a class="tf-card" href="<?php echo get_permalink($p->ID); ?>">
<div class="tf-card-img"><img src="<?php echo esc_attr($img); ?>" alt="<?php echo esc_attr($p->post_title); ?>" loading="lazy"></div>
<div class="tf-card-body">
<div class="tf-card-meta"><span class="tf-tag tf-tag-gaming"><?php echo esc_html($cat); ?></span><span class="tf-card-date"><?php echo date('M j, Y',strtotime($p->post_date)); ?></span></div>
<h3><?php echo esc_html($p->post_title); ?></h3>
<p><?php echo esc_html(wp_trim_words(strip_tags($p->post_content),20)); ?></p>
<span class="tf-read" style="font-size:13.5px">Read more &rarr;</span>
</div></a>
<?php endforeach; ?>
</div></div></section>
<?php endif; 
?>
<div class="tf-guides"><div class="tf-guides-in">
<div class="tf-guides-head"><h2>Quick Setup Guides</h2></div>
<a class="tf-guide-item" href="#"><div class="tf-guide-num">01</div><div class="tf-guide-icon">&#128256;</div><div><div class="tf-guide-cat">HDMI Switch</div><div class="tf-guide-title">How to connect PS5 + Xbox + Apple TV to one display</div></div></a>
<div class="tf-guide-div"></div>
<a class="tf-guide-item" href="#"><div class="tf-guide-num">02</div><div class="tf-guide-icon">&#128187;</div><div><div class="tf-guide-cat">Troubleshooting</div><div class="tf-guide-title">Black screen on HDMI switch — causes and fixes</div></div></a>
<div class="tf-guide-div"></div>
<a class="tf-guide-item" href="#"><div class="tf-guide-num">03</div><div class="tf-guide-icon">&#128266;</div><div><div class="tf-guide-cat">eARC</div><div class="tf-guide-title">Getting Dolby Atmos through a switch to your soundbar</div></div></a>
</div></div>
<div class="tf-nl"><h2>Get setup guides in your inbox</h2><p>New articles, troubleshooting tips and product news — no spam.</p><div class="tf-nl-row"><input type="email" placeholder="your@email.com"><button>Subscribe</button></div><div class="tf-nl-note">Free forever &middot; Unsubscribe anytime</div></div>
<div class="tf-trust-bar"><div class="tf-trust-grid">
<div class="tf-trust-cell"><div style="font-size:28px">&#9997;&#65039;</div><h4>Written by AV Engineers</h4><p>Not sponsored</p></div>
<div class="tf-trust-cell"><div style="font-size:28px">&#128295;</div><h4>Setup Guides</h4><p>Step-by-step</p></div>
<div class="tf-trust-cell"><div style="font-size:28px">&#129379;</div><h4>Always Free</h4><p>No account needed</p></div>
<div class="tf-trust-cell"><div style="font-size:28px">&#128236;</div><h4>Weekly Updates</h4><p>New guides every week</p></div>
</div></div>
<?php
get_footer();
exit;
}
/* ===== /MRO_TECH_FORUM ===== */

// VX page — hide admin bar + suppress cookie plugins
add_filter('show_admin_bar',function($v){return is_page(13289)?false:$v;});
add_action('wp_enqueue_scripts',function(){if(!is_page(13289))return;wp_dequeue_script('cky-script');wp_dequeue_style('cky-style');wp_dequeue_script('cmplz-cookiebanner');wp_dequeue_style('cmplz-cookiebanner');},PHP_INT_MAX);


// ── 产品页五点图标：包裹已有图标 ──
add_action('wp_enqueue_scripts',function(){
    if(is_product()){wp_enqueue_style('tabler-icons-mro','https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.x/dist/tabler-icons.min.css',[],null,'print');}
add_filter('style_loader_tag',function($t,$h){if($h==='tabler-icons-mro'){return str_replace("media='print'","media='print' onload=\"this.media='all'\"",$t);}return $t;},10,2);
});
add_action('wp_footer',function(){
    if(!is_product())return;
    ?>
    <script data-cfasync="false">
    (function(){
        if(document.getElementById('mro-li-done'))return;
        document.body.appendChild(Object.assign(document.createElement('div'),{id:'mro-li-done'}));
        function wrap(){
            document.querySelectorAll('.woocommerce-product-details__short-description ul li').forEach(function(li){
                if(li.querySelector('.mro-li-icon'))return;
                var w=document.createElement('span');w.className='mro-li-icon';
                var fc=li.firstElementChild;
                if(fc&&fc.tagName==='I'&&fc.classList.contains('ti')){
                    li.insertBefore(w,fc);w.appendChild(fc);
                }else{
                    var i=document.createElement('i');i.className='ti ti-check';i.setAttribute('aria-hidden','true');
                    w.appendChild(i);li.insertBefore(w,li.firstChild);
                }
            });
        }
        document.readyState==='loading'?document.addEventListener('DOMContentLoaded',wrap):wrap();
    })();
    </script>
    <?php
},20);

// ── VX 页面字体覆盖：vx-sub 字号放大 ──
add_action('wp_head',function(){
    if(!is_page(13289))return;
    echo '<style>.vx-sub{font-size:15px !important;line-height:1.65 !important}</style>';
});



// ── Checkout trust bar (above form, below nav) ──
add_action('woocommerce_before_checkout_form','mrocioa_ck_trust_bar',1);
function mrocioa_ck_trust_bar(){
    if(!is_checkout())return;
    echo '<div class="mro-ck-bar"><div class="mro-ck-bar-inner"><div class="mro-ck-bar-item"><div class="mro-ck-bar-val">SSL</div><div class="mro-ck-bar-label">256-bit Encrypted</div></div><div class="mro-ck-bar-item"><div class="mro-ck-bar-val">90</div><div class="mro-ck-bar-label">Day Returns</div></div><div class="mro-ck-bar-item"><div class="mro-ck-bar-val">Free</div><div class="mro-ck-bar-label">Worldwide Shipping</div></div><div class="mro-ck-bar-item"><div class="mro-ck-bar-val">4.8&#9733;</div><div class="mro-ck-bar-label">Avg Rating</div></div></div></div>';
}

// ══════════════════════════════════════════════════════════════
// MROCIOA CHECKOUT REDESIGN v3 — 2026-06
// NOTE: 不改导航栏/footer，只改结账内容区域
// ══════════════════════════════════════════════════════════════

// A. Move payment to left col via JS (right col selector, multi-delay)
add_action('wp_footer','mrocioa_ck_js');
function mrocioa_ck_js(){
    if(!is_checkout())return;
    ?>
    <script data-cfasync="false">
    (function($){
        function mroLayout(){
            
            var $or = $(".checkout-order-review");
            if($or.length && !$or.find(".mro-order-h").length){
                $or.prepend('<h3 class="mro-card-h mro-order-h">Order summary</h3>');
            }
        }
        $(document).ready(function(){
            setTimeout(mroLayout, 300);
            setTimeout(mroLayout, 800);
            $(document.body).on("updated_checkout", function(){
                setTimeout(mroLayout, 300);
                setTimeout(mroLayout, 800);
            });
        });
    })(jQuery);
    </script>
    <?php
}

// B. Checkout page CSS (content area only, nav/footer untouched)
add_action('wp_head','mrocioa_ck_css');
function mrocioa_ck_css(){
    if(!is_checkout())return;
    echo '<style id="mro-ck-css">
/* ── BACKGROUND & FONT ── */
body.woocommerce-checkout{background:#f5f5f7!important;font-family:Inter,sans-serif}
/* ── HIDE: breadcrumbs, coupon toggle, old trust bars ── */
body.woocommerce-checkout .woocommerce-breadcrumb,body.woocommerce-checkout .entry-title{display:none!important}
body.woocommerce-checkout .woocommerce-form-coupon-toggle,body.woocommerce-checkout .woocommerce-form-coupon{display:none!important}
body.woocommerce-checkout .mro-ck-trust,body.woocommerce-checkout .mro-checkout-trust{display:none!important}
.mro-ck-bar{background:#0d1a3e;width:100vw;margin-left:calc(-50vw + 50%);padding:40px 32px;box-sizing:border-box;position:relative}
.mro-ck-bar-inner{max-width:960px;margin:0 auto;display:flex;align-items:center;justify-content:space-around;gap:24px;flex-wrap:wrap}
.mro-ck-bar-item{display:flex;flex-direction:column;align-items:center;gap:8px;text-align:center}
.mro-ck-bar-val{font-family:"Space Grotesk",sans-serif;font-size:32px;font-weight:700;color:#37D5F2;line-height:1;letter-spacing:-.5px}
.mro-ck-bar-label{font-family:Inter,sans-serif;font-size:13px;color:#8899bb;letter-spacing:.5px;font-weight:400}
/* ── PAGE CONTENT WRAPPER ── */
body.woocommerce-checkout main,body.woocommerce-checkout .wd-content-area,body.woocommerce-checkout .wd-content-layout{background:#f5f5f7!important}
body.woocommerce-checkout .entry-content{padding:0!important;max-width:100%!important;background:transparent!important}
body.woocommerce-checkout .wd-content-layout.container{padding:0!important;max-width:100%!important}
body.woocommerce-checkout .woocommerce{max-width:1200px;margin:0 auto;padding:28px 32px!important;background:transparent!important}
/* ── CHECKOUT FORM: 2-col grid ── */
body.woocommerce-checkout form.woocommerce-checkout{display:grid!important;grid-template-columns:1fr 1fr!important;gap:24px!important;align-items:stretch!important}
body.woocommerce-checkout .customer-details{width:100%!important;max-width:none!important;min-width:0}
body.woocommerce-checkout wc-order-attribution-inputs{display:none!important;margin:0!important}
body.woocommerce-checkout .woocommerce-additional-fields{border-bottom-left-radius:12px!important;border-bottom-right-radius:12px!important}
body.woocommerce-checkout .woocommerce-billing-fields{margin-top:0!important}
body.woocommerce-checkout .customer-details{display:flex!important;flex-direction:column!important}
body.woocommerce-checkout .woocommerce-additional-fields{flex:1!important;display:flex!important;flex-direction:column!important;margin-bottom:0!important}
body.woocommerce-checkout .woocommerce-additional-fields .form-row{flex:1!important;display:flex!important;flex-direction:column!important;margin-bottom:0!important}
body.woocommerce-checkout .woocommerce-additional-fields textarea{flex:1!important;display:block!important;resize:none!important;min-height:80px!important;width:100%!important;box-sizing:border-box!important}
body.woocommerce-checkout .woocommerce-additional-fields__field-wrapper{flex:1!important;display:flex!important;flex-direction:column!important}
body.woocommerce-checkout .woocommerce-additional-fields textarea{height:100%!important}
body.woocommerce-checkout .checkout-order-review{width:100%!important;max-width:none!important;min-width:0}
body.woocommerce-checkout .checkout-order-review .woocommerce-checkout-review-order,body.woocommerce-checkout .woocommerce-checkout-review-order{display:flex!important;flex-direction:column!important;height:100%!important;box-sizing:border-box!important}
body.woocommerce-checkout .wd-table-wrapper{flex-shrink:0!important}
body.woocommerce-checkout .woocommerce-checkout-payment{flex:1!important;min-height:180px!important;overflow:auto!important}
/* ── CARDS: billing / shipping / additional / payment ── */
.mro-card,.mro-pay-wrap,body.woocommerce-checkout .woocommerce-billing-fields,body.woocommerce-checkout .woocommerce-shipping-fields,body.woocommerce-checkout .woocommerce-additional-fields{background:#fff!important;border-radius:0!important;border:1px solid #e8e8e8!important;padding:22px!important;margin-bottom:16px}
/* ── CARD HEADINGS ── */
.mro-card-h,body.woocommerce-checkout .woocommerce-billing-fields h3,body.woocommerce-checkout .woocommerce-shipping-fields h3,body.woocommerce-checkout .woocommerce-additional-fields h3{font-family:"Space Grotesk",sans-serif!important;font-size:15px!important;font-weight:600!important;color:#1d1d1f!important;margin:0 0 18px!important;padding:0!important;border:none!important;text-transform:none!important}
body.woocommerce-checkout .checkout-order-review>h3:not(.mro-card-h),body.woocommerce-checkout .checkout-order-review>h3:not(.mro-order-h){display:none!important}
.mro-order-h{display:none!important;margin:0!important}
/* ── FORM ROWS ── */
body.woocommerce-checkout .form-row{padding:0!important;margin:0 0 12px!important}
body.woocommerce-checkout .form-row-first,body.woocommerce-checkout .form-row-last{width:calc(50% - 6px)!important;float:left!important}
body.woocommerce-checkout .form-row-first{margin-right:12px!important}
body.woocommerce-checkout .form-row-wide{clear:both!important;width:100%!important;float:none!important}
body.woocommerce-checkout .woocommerce-billing-fields__field-wrapper:after,body.woocommerce-checkout .woocommerce-shipping-fields__field-wrapper:after{content:"";display:table;clear:both}
/* ── LABELS ── */
body.woocommerce-checkout .form-row label{display:block!important;font-size:12px!important;font-weight:500!important;color:#555!important;margin-bottom:5px!important;line-height:1.3!important}
body.woocommerce-checkout .form-row abbr{color:#7B6CF6;text-decoration:none}
/* ── INPUTS ── */
body.woocommerce-checkout .form-row .input-text,body.woocommerce-checkout .form-row select,body.woocommerce-checkout .form-row textarea{width:100%!important;background:#fff!important;border:1.5px solid #d4d4d4!important;border-radius:8px!important;padding:10px 13px!important;color:#1d1d1f!important;font-size:14px!important;font-family:Inter,sans-serif!important;outline:none!important;box-shadow:none!important;height:auto!important;min-height:0!important;transition:border-color .15s!important}
body.woocommerce-checkout .form-row .input-text:focus,body.woocommerce-checkout .form-row select:focus{border-color:#7B6CF6!important}
/* ── SHIP TO DIFFERENT ── */
body.woocommerce-checkout #ship-to-different-address{font-size:13px;color:#555}
body.woocommerce-checkout #ship-to-different-address label{font-size:13px!important;color:#555!important;font-weight:500!important;display:inline!important}
/* ── RIGHT COL: order review card ── */
body.woocommerce-checkout .checkout-order-review .woocommerce-checkout-review-order,body.woocommerce-checkout .woocommerce-checkout-review-order{background:#fff!important;border-radius:12px!important;border:1px solid #e8e8e8!important;padding:22px!important;overflow:hidden!important}
body.woocommerce-checkout .wd-table-wrapper{overflow:visible!important;border:none!important;background:transparent!important}
body.woocommerce-checkout .checkout-order-review .woocommerce-checkout-review-order,body.woocommerce-checkout .woocommerce-checkout-review-order{width:100%!important;box-sizing:border-box!important;border-radius:12px!important;overflow:hidden!important}
body.woocommerce-checkout .checkout-order-review{padding:0!important;margin:0!important}
/* ── ORDER TABLE ── */
body.woocommerce-checkout .woocommerce-checkout-review-order-table{width:100%!important;border:none!important;border-collapse:collapse}
body.woocommerce-checkout .woocommerce-checkout-review-order-table thead{display:none!important}
body.woocommerce-checkout .woocommerce-checkout-review-order-table .cart-item td{padding:11px 0!important;border-bottom:1px solid #f2f2f2!important;font-size:13px!important;color:#1d1d1f!important;background:transparent!important;vertical-align:middle!important}
body.woocommerce-checkout .woocommerce-checkout-review-order-table .cart-item .product-name{font-weight:500!important}
body.woocommerce-checkout .woocommerce-checkout-review-order-table .cart-item .product-total{text-align:right!important;font-weight:600!important}
body.woocommerce-checkout .woocommerce-checkout-review-order-table tfoot td,body.woocommerce-checkout .woocommerce-checkout-review-order-table tfoot th{padding:6px 0!important;font-size:13px!important;color:#666!important;border:none!important;background:transparent!important}
body.woocommerce-checkout .woocommerce-checkout-review-order-table .order-total th,body.woocommerce-checkout .woocommerce-checkout-review-order-table .order-total td{font-size:16px!important;font-weight:700!important;color:#1d1d1f!important;padding-top:12px!important;border-top:1px solid #ebebeb!important}
body.woocommerce-checkout .woocommerce-checkout-review-order-table .order-total .woocommerce-Price-amount{color:#7B6CF6!important}
/* ── PAYMENT (after move to left) ── */
body.woocommerce-checkout .mro-pay-wrap .woocommerce-checkout-payment{background:transparent!important;border:none!important;padding:0!important;margin-top:0!important}
body.woocommerce-checkout .wc_payment_methods{list-style:none!important;margin:0 0 12px!important;padding:0!important;border:none!important}
body.woocommerce-checkout .wc_payment_method{border:1.5px solid #e5e5e5!important;border-radius:10px!important;margin-bottom:8px!important;overflow:hidden!important;background:#fafafa!important}
body.woocommerce-checkout .wc_payment_method>label{display:flex!important;align-items:center!important;gap:10px!important;padding:12px 16px!important;cursor:pointer!important;font-size:13px!important;font-weight:500!important;color:#1d1d1f!important;margin:0!important;width:auto!important}
body.woocommerce-checkout .wc_payment_method .payment_box{padding:14px 16px!important;background:#fff!important;border-top:1px solid #f0f0f0!important}
body.woocommerce-checkout .wc_payment_method .payment_box .form-row{margin:0 0 10px!important}
body.woocommerce-checkout .wc_payment_method .payment_box .input-text{background:#fff!important}
/* ── PLACE ORDER BUTTON ── */
body.woocommerce-checkout .form-row.place-order{margin:0!important;padding:0!important;width:100%!important;clear:both!important;float:none!important}
body.woocommerce-checkout #place_order{width:100%!important;background:#1d1d1f!important;color:#fff!important;border:none!important;border-radius:10px!important;padding:15px!important;font-size:15px!important;font-weight:700!important;cursor:pointer!important;font-family:"Space Grotesk",sans-serif!important;margin-top:14px!important;text-transform:none!important;box-shadow:none!important;display:block!important;transition:background .2s!important}
body.woocommerce-checkout #place_order:hover{background:#333!important}
/* ── PRIVACY / TERMS ── */
body.woocommerce-checkout .woocommerce-terms-and-conditions-wrapper,body.woocommerce-checkout .woocommerce-privacy-policy-text{font-size:11px!important;color:#aaa!important;text-align:center!important;margin-top:8px!important;line-height:1.6!important}
/* ── PPCP ── */
body.woocommerce-checkout .ppc-button-wrapper{margin-top:8px!important}
/* ── NOTICES ── */
body.woocommerce-checkout .woocommerce-notices-wrapper{margin-bottom:12px}
body.woocommerce-checkout .ppcp-dcc-order-button,body.woocommerce-checkout #ppcp-hosted-fields{display:none!important}
body.woocommerce-checkout .customer-details,body.woocommerce-checkout .checkout-order-review{margin-top:0!important;vertical-align:top}
/* ── MOBILE RESPONSIVE ── */
@media(max-width:767px){body.woocommerce-checkout form.woocommerce-checkout{grid-template-columns:1fr!important;gap:16px!important;width:100%!important}body.woocommerce-checkout .form-row-first,body.woocommerce-checkout .form-row-last{width:100%!important;margin-right:0!important;float:none!important}body.woocommerce-checkout .checkout-order-review{padding:16px 0!important}body.woocommerce-checkout .woocommerce-checkout-review-order,body.woocommerce-checkout #order_review{width:100%!important;padding-left:0!important;padding-right:0!important}body.woocommerce-checkout #payment,body.woocommerce-checkout .woocommerce-checkout-payment{width:100%!important;padding-left:0!important;padding-right:0!important}body.woocommerce-checkout #place_order{width:100%!important;display:block!important;box-sizing:border-box!important}body.woocommerce-checkout .woocommerce{padding-left:16px!important;padding-right:16px!important}}body.woocommerce-checkout #payment ul.payment_methods{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px}body.woocommerce-checkout #payment ul.payment_methods li.wc_payment_method{border:1.5px solid #e4e8f0;border-radius:12px;background:#fff;padding:0;overflow:hidden}body.woocommerce-checkout #payment ul.payment_methods li.wc_payment_method:has(input:checked){border-color:#7B6CF6;background:#f8f7ff}body.woocommerce-checkout #payment .wc_payment_method input.input-radio{display:none}body.woocommerce-checkout #payment .wc_payment_method>label{display:flex;align-items:center;gap:10px;padding:14px 16px;font-size:14px;font-weight:600;color:#0B0E14;cursor:pointer;margin:0}body.woocommerce-checkout #payment .wc_payment_method>label::before{content:"";flex-shrink:0;width:18px;height:18px;border:2px solid #ccc;border-radius:50%;background:#fff;transition:border-color .2s,background .2s}body.woocommerce-checkout #payment .wc_payment_method input:checked+label::before{border-color:#7B6CF6;background:#7B6CF6;box-shadow:inset 0 0 0 4px #fff}body.woocommerce-checkout #payment .payment_box{padding:16px;border-top:1px solid #e4e8f0;background:#f9f9fb}
}</style>';
}

add_action('wp_footer','mrocioa_ck_radius');
function mrocioa_ck_radius(){
    if(!is_checkout())return;
    ?>
    <script data-cfasync="false">
    document.addEventListener('DOMContentLoaded',function(){
        function fixR(){
            var els=document.querySelectorAll('.woocommerce-additional-fields');
            els.forEach(function(el){
                el.style.setProperty('border-bottom-left-radius','12px','important');
                el.style.setProperty('border-bottom-right-radius','12px','important');
            });
        }
        fixR();setTimeout(fixR,500);setTimeout(fixR,1200);
    });
    </script>
    <?php
}


/* ─── MRO INFO PAGES ─────────────────────────── */
/*
 * ══════════════════════════════════════════════════════════════
 * MRO — INFO & POLICY PAGES · STYLE A
 * Add to: woodmart-child / functions.php
 * OUTSIDE all frozen blocks. Back up functions.php first.
 *
 * Covers:
 *   B. Contact Us
 *   C. About Us
 *   D. Privacy Policy
 *   E. Refund & Returns
 *   F. Shipping
 *   G. Track Order
 * ══════════════════════════════════════════════════════════════
 */

/* ─────────────────────────────────────────────────────────────
 * A. SHARED CSS — injected on all 6 info/policy pages
 * ─────────────────────────────────────────────────────────────*/
function mro_info_styles() {
    $slugs = [
        'contact-us','contact',
        'about-us','about',
        'privacy-policy',
        'refund_returns','refund-returns','refund-returns-policy',
        'returns-policy','refund-policy','refund-and-returns-policy',
        'shipping','shipping-policy','shipping-information',
        'track-order','order-tracking','track-your-order','track',
    ];
    if ( ! is_page( $slugs ) ) return;
    ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" crossorigin>
<style id="mro-info-css">
.mro-w{font-family:"Inter",sans-serif;color:#1A2030}
.mro-w *,.mro-w *::before,.mro-w *::after{box-sizing:border-box}
.entry-title{display:none!important}

/* full-bleed */
.mro-bleed{margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);width:100vw;position:relative}

/* ── Hero ── */
.mro-hero{background:linear-gradient(155deg,#0B0E14 0%,#12161F 55%,#1A2030 100%);padding:88px 24px 72px;text-align:center;overflow:hidden}
.mro-hero::after{content:"";position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% -5%,rgba(123,108,246,.22) 0%,transparent 65%);pointer-events:none}
.mro-hi{position:relative;z-index:1;max-width:720px;margin:0 auto}
.mro-eye{font-family:"IBM Plex Mono",monospace;font-size:11px;letter-spacing:.15em;color:#37D5F2;text-transform:uppercase;display:block;margin-bottom:18px}
.mro-hero h1{font-family:"Space Grotesk",sans-serif;font-size:clamp(30px,5vw,52px);font-weight:700;line-height:1.12;color:#fff;margin:0 0 18px}
.mro-hero h1 em{color:#7B6CF6;font-style:normal}
.mro-hero-sub{font-size:17px;color:rgba(255,255,255,.58);line-height:1.65;max-width:520px;margin:0 auto}
.mro-pill{display:inline-flex;align-items:center;gap:8px;background:rgba(55,213,242,.1);border:1px solid rgba(55,213,242,.25);border-radius:999px;padding:7px 18px;font-family:"IBM Plex Mono",monospace;font-size:12px;color:#37D5F2;margin-top:26px}

/* ── Section wrappers ── */
.mro-sec{padding:64px 24px;max-width:920px;margin:0 auto}
.mro-inner{max-width:680px;margin:0 auto}
.mro-inner-w{max-width:920px;margin:0 auto}
.mro-lbl{font-family:"IBM Plex Mono",monospace;font-size:11px;letter-spacing:.14em;color:#7B6CF6;text-transform:uppercase;display:block;margin-bottom:10px}
.mro-h2{font-family:"Space Grotesk",sans-serif;font-size:clamp(22px,3.2vw,34px);font-weight:700;color:#0B0E14;margin:0 0 14px}
.mro-sub{font-size:16px;color:#5A6478;line-height:1.75;margin:0 0 40px}

/* ── Grids ── */
.mro-g3{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.mro-g4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
.mro-g2{display:grid;grid-template-columns:repeat(2,1fr);gap:48px;align-items:center}
@media(max-width:768px){.mro-g3,.mro-g2{grid-template-columns:1fr}.mro-g4{grid-template-columns:repeat(2,1fr)}}
@media(max-width:480px){.mro-g4{grid-template-columns:1fr}}

/* ── Cards ── */
.mro-card{background:#F8F9FB;border:1px solid #E4E8F0;border-radius:14px;padding:28px 24px;transition:border-color .2s,box-shadow .2s}
.mro-card:hover{border-color:rgba(123,108,246,.35);box-shadow:0 6px 28px rgba(123,108,246,.09)}
.mro-ci{width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#7B6CF6 0%,#37D5F2 100%);display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:18px}
.mro-card h3{font-family:"Space Grotesk",sans-serif;font-size:16px;font-weight:600;color:#0B0E14;margin:0 0 8px}
.mro-card p{font-size:14px;color:#5A6478;margin:0;line-height:1.65}
.mro-card a{color:#7B6CF6;text-decoration:none;font-weight:500}
.mro-card a:hover{text-decoration:underline}

/* ── Stats bar ── */
.mro-stats{background:linear-gradient(135deg,#0B0E14 0%,#12161F 100%);padding:52px 24px}
.mro-si{max-width:900px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);text-align:center;gap:24px}
@media(max-width:600px){.mro-si{grid-template-columns:repeat(2,1fr)}}
.mro-sn{font-family:"Space Grotesk",sans-serif;font-size:38px;font-weight:700;color:#7B6CF6;display:block;line-height:1}
.mro-sn em{color:#37D5F2;font-style:normal}
.mro-sl{font-family:"IBM Plex Mono",monospace;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.4);display:block;margin-top:7px}

/* ── Pillars ── */
.mro-pillars{display:grid;grid-template-columns:repeat(3,1fr);border:1px solid #E4E8F0;border-radius:16px;overflow:hidden;margin:40px 0}
@media(max-width:640px){.mro-pillars{grid-template-columns:1fr}}
.mro-pillar{padding:36px 28px;border-right:1px solid #E4E8F0}
.mro-pillars .mro-pillar:last-child{border-right:none}
.mro-pnum{font-family:"IBM Plex Mono",monospace;font-size:48px;font-weight:500;color:rgba(123,108,246,.12);line-height:1;display:block;margin-bottom:14px}
.mro-pillar h3{font-family:"Space Grotesk",sans-serif;font-size:18px;font-weight:700;color:#0B0E14;margin:0 0 10px}
.mro-pillar p{font-size:14px;color:#5A6478;line-height:1.7;margin:0}

/* ── Steps ── */
.mro-steps{display:grid;grid-template-columns:repeat(3,1fr);position:relative;margin:40px 0}
.mro-steps::before{content:"";position:absolute;top:21px;left:calc(100%/6);right:calc(100%/6);height:2px;background:linear-gradient(90deg,#7B6CF6,#37D5F2)}
.mro-step{text-align:center;position:relative;z-index:1;padding:0 12px}
.mro-step-n{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#7B6CF6,#5A4FD4);color:#fff;font-family:"Space Grotesk",sans-serif;font-size:17px;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 0 0 4px rgba(123,108,246,.15)}
.mro-step h4{font-family:"Space Grotesk",sans-serif;font-size:15px;font-weight:600;color:#0B0E14;margin:0 0 8px}
.mro-step p{font-size:13px;color:#5A6478;margin:0;line-height:1.6}
@media(max-width:520px){.mro-steps{grid-template-columns:1fr;gap:20px}.mro-steps::before{display:none}}

/* ── Policy typography ── */
.mro-pol{font-size:15px;line-height:1.85;color:#3A4556;padding:56px 24px 80px;max-width:800px;margin:0 auto}
.mro-pol h2{font-family:"Space Grotesk",sans-serif;font-size:20px;font-weight:600;color:#0B0E14;margin:48px 0 14px;padding-top:20px;border-top:1px solid #E4E8F0}
.mro-pol h3{font-family:"Space Grotesk",sans-serif;font-size:16px;font-weight:600;color:#1A2030;margin:28px 0 10px}
.mro-pol p{margin:0 0 16px}
.mro-pol ul,.mro-pol ol{padding-left:22px;margin:0 0 16px}
.mro-pol li{margin-bottom:8px}
.mro-pol a{color:#7B6CF6}
.mro-pol strong{color:#0B0E14;font-weight:600}

/* ── Form styling ── */
.mro-form-box{background:#fff;border:1px solid #E4E8F0;border-radius:16px;padding:40px}
.mro-w input[type=text],.mro-w input[type=email],.mro-w input[type=tel],.mro-w textarea,.mro-w select{border:1.5px solid #D4D9E6!important;border-radius:8px!important;padding:12px 16px!important;font-family:"Inter",sans-serif!important;font-size:14px!important;width:100%!important;background:#fff!important;color:#1A2030!important;transition:border-color .2s,box-shadow .2s!important;display:block!important;margin-bottom:14px!important;box-sizing:border-box!important}
.mro-w input:focus,.mro-w textarea:focus{border-color:#7B6CF6!important;outline:none!important;box-shadow:0 0 0 3px rgba(123,108,246,.1)!important}
.mro-w input[type=submit],.mro-w button[type=submit],.mro-w .woocommerce button.button,.mro-w .woocommerce a.button{background:linear-gradient(135deg,#7B6CF6,#5A4FD4)!important;color:#fff!important;border:none!important;padding:13px 32px!important;font-family:"Space Grotesk",sans-serif!important;font-size:15px!important;font-weight:600!important;border-radius:8px!important;cursor:pointer!important;transition:transform .15s,box-shadow .15s!important;display:inline-block!important;width:auto!important;margin-top:8px!important;text-decoration:none!important;letter-spacing:.01em!important}
.mro-w input[type=submit]:hover,.mro-w button[type=submit]:hover{transform:translateY(-1px)!important;box-shadow:0 6px 22px rgba(123,108,246,.3)!important}
.mro-w label{font-size:13px;font-weight:500;color:#3A4556;display:block;margin-bottom:6px}
.mro-w .wpcf7-not-valid-tip{color:#E84040;font-size:12px;display:block;margin-top:-10px;margin-bottom:8px}
.mro-w .wpcf7-mail-sent-ok{background:rgba(55,213,242,.1);border:1px solid rgba(55,213,242,.3);color:#0B8FA0;border-radius:8px;padding:14px 18px;margin-top:12px;font-size:14px}

/* ── Shipping table ── */
.mro-tbl{width:100%;border-collapse:collapse;margin:28px 0;font-size:14px;border-radius:12px;overflow:hidden}
.mro-tbl th{background:#12161F;color:rgba(255,255,255,.85);font-family:"Space Grotesk",sans-serif;font-weight:600;padding:14px 18px;text-align:left;font-size:13px}
.mro-tbl td{padding:13px 18px;border-bottom:1px solid #E4E8F0;color:#3A4556;vertical-align:middle}
.mro-tbl tr:last-child td{border-bottom:none}
.mro-tbl tr:nth-child(even) td{background:#F8F9FB}
.mro-badge{display:inline-block;padding:3px 10px;border-radius:999px;font-size:12px;font-family:"IBM Plex Mono",monospace;letter-spacing:.02em}
.mro-bd-f{background:rgba(55,213,242,.12);color:#0B8FA0}
.mro-bd-s{background:rgba(123,108,246,.1);color:#5A4FD4}

/* ── Notice ── */
.mro-note{background:rgba(123,108,246,.07);border-left:4px solid #7B6CF6;border-radius:0 10px 10px 0;padding:16px 20px;margin:24px 0;font-size:14px;color:#3A4556;line-height:1.65}
.mro-note strong{color:#7B6CF6;font-weight:600}

/* ── Track status visual ── */
.mro-track-vis{display:flex;justify-content:space-between;margin:40px 0;position:relative;padding:0 20px}
.mro-track-vis::before{content:"";position:absolute;top:18px;left:60px;right:60px;height:2px;background:linear-gradient(90deg,#7B6CF6,#37D5F2)}
.mro-tv-item{text-align:center;position:relative;z-index:1;flex:1}
.mro-tv-dot{width:36px;height:36px;border-radius:50%;border:2px solid #D4D9E6;background:#F8F9FB;margin:0 auto 10px;display:flex;align-items:center;justify-content:center;font-size:14px}
.mro-tv-dot.mro-active{background:linear-gradient(135deg,#7B6CF6,#37D5F2);border:none}
.mro-tv-item span{font-size:11px;color:#8A96A8;font-family:"IBM Plex Mono",monospace;text-transform:uppercase;letter-spacing:.07em;display:block}
@media(max-width:480px){.mro-track-vis{flex-direction:column;gap:16px;align-items:flex-start;padding:0}.mro-track-vis::before{display:none}.mro-tv-item{display:flex;align-items:center;gap:12px}.mro-tv-dot{margin:0;flex-shrink:0}}

/* ── About: story visual ── */
.mro-story-vis{background:linear-gradient(135deg,#0B0E14,#12161F);border:1px solid rgba(255,255,255,.07);border-radius:20px;padding:40px 32px;display:flex;flex-direction:column;gap:18px}
.mro-kv{display:flex;align-items:center;gap:16px}
.mro-kv-ico{font-size:24px;flex-shrink:0;width:40px;text-align:center}
.mro-kv-text span{font-family:"Space Grotesk",sans-serif;font-size:15px;font-weight:600;color:#fff;display:block}
.mro-kv-text small{font-family:"IBM Plex Mono",monospace;font-size:11px;color:rgba(255,255,255,.38)}

/* ── About: category tiles ── */
.mro-cat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin:40px 0}
@media(max-width:680px){.mro-cat-grid{grid-template-columns:repeat(2,1fr)}}
.mro-cat-tile{background:#12161F;border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:24px 16px;text-align:center;text-decoration:none;display:block;transition:border-color .2s,transform .2s}
.mro-cat-tile:hover{border-color:rgba(123,108,246,.4);transform:translateY(-3px)}
.mro-cat-tile-ico{font-size:28px;display:block;margin-bottom:12px}
.mro-cat-tile-name{font-family:"Space Grotesk",sans-serif;font-size:13px;font-weight:600;color:#fff;display:block;margin-bottom:4px}
.mro-cat-tile-sub{font-family:"IBM Plex Mono",monospace;font-size:11px;color:rgba(255,255,255,.38);display:block}

/* ── Background bands ── */
.mro-bg-light{background:#F8F9FB;padding:64px 24px}
.mro-bg-dark{background:#0B0E14;padding:64px 24px}

/* ── CTA ── */
.mro-cta{background:linear-gradient(135deg,#7B6CF6 0%,#5A4FD4 50%,#4AAFC9 100%);padding:72px 24px;text-align:center}
.mro-cta h2{font-family:"Space Grotesk",sans-serif;font-size:clamp(24px,4vw,36px);font-weight:700;color:#fff;margin:0 0 14px}
.mro-cta p{color:rgba(255,255,255,.75);font-size:16px;margin:0 0 32px}
.mro-btn{display:inline-flex;align-items:center;gap:8px;padding:14px 30px;border-radius:9px;font-family:"Space Grotesk",sans-serif;font-size:15px;font-weight:600;text-decoration:none;transition:transform .15s,box-shadow .15s}
.mro-btn-w{background:#fff;color:#7B6CF6}
.mro-btn-w:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(0,0,0,.18);color:#5A4FD4}
.mro-btn-g{background:transparent;color:#fff;border:2px solid rgba(255,255,255,.38);margin-left:14px}
.mro-btn-g:hover{border-color:#fff;color:#fff}

/* ── Story text ── */
.mro-story-body{font-size:16px;line-height:1.8;color:#3A4556}
.mro-story-body p{margin:0 0 20px}
</style>
    <?php
}
add_action( 'wp_head', 'mro_info_styles', 25 );
add_action( 'wp_head', function() {
    if ( ! is_page( ['about-us','about'] ) ) return;
    echo '<style>body.page-id-9499 .whb-main-header{position:relative!important}body.page-id-9499 header.whb-header{min-height:auto!important}</style>';
}, 26 );


/* ─────────────────────────────────────────────────────────────
 * B. CONTACT US
 * ─────────────────────────────────────────────────────────────*/
function mro_contact_page( $content ) {
    if ( ! is_main_query() || ! in_the_loop() ) return $content;
    if ( ! is_page( [ 'contact-us', 'contact' ] ) ) return $content;
    if ( strpos( $content, 'mro-w' ) !== false ) return $content;
    ob_start();
    ?>
<style>.mro-ct-split{display:grid;grid-template-columns:1fr 1.6fr;gap:60px;align-items:start;padding:64px 0 80px}.mro-ct-left .mro-eye{margin-bottom:10px}.mro-ct-left h2{font-family:"Space Grotesk",sans-serif;font-size:2rem;font-weight:600;line-height:1.2;margin:10px 0 32px;color:#fff}.mro-addr-rows{display:flex;flex-direction:column;gap:20px}.mro-addr-row{display:flex;gap:16px;align-items:flex-start}.mro-addr-ic{width:44px;height:44px;border-radius:10px;background:rgba(123,108,246,.15);border:1px solid rgba(123,108,246,.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:20px;line-height:1}.mro-addr-lb{font-family:"Space Grotesk",sans-serif;font-size:.72rem;font-weight:600;color:#7B6CF6;letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px}.mro-addr-vl{color:rgba(255,255,255,.65);font-size:.92rem;line-height:1.55}.mro-addr-vl a{color:#37D5F2;text-decoration:none}.mro-ct-right{background:#12161F;border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:36px 40px}.mro-ct-right .wpcf7-form p>label{display:block;font-size:.75rem;font-weight:600;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;font-family:"Space Grotesk",sans-serif}.mro-ct-right .wpcf7-form p{margin:0 0 18px}.mro-ct-right .wpcf7-form input[type=text],.mro-ct-right .wpcf7-form input[type=email],.mro-ct-right .wpcf7-form input[type=tel],.mro-ct-right .wpcf7-form textarea{width:100%;padding:11px 16px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#fff;font-family:"Inter",sans-serif;font-size:.92rem;outline:none;transition:border-color .2s;box-sizing:border-box}.mro-ct-right .wpcf7-form input:focus,.mro-ct-right .wpcf7-form textarea:focus{border-color:#7B6CF6;background:rgba(123,108,246,.06)}.mro-ct-right .wpcf7-form textarea{height:120px;resize:vertical}.mro-ct-right .wpcf7-form input[type=submit]{background:linear-gradient(135deg,#7B6CF6,#37D5F2);color:#fff;border:none;padding:14px 32px;border-radius:8px;font-family:"Space Grotesk",sans-serif;font-weight:600;font-size:.95rem;cursor:pointer;width:100%;transition:opacity .2s,transform .15s;letter-spacing:.02em}.mro-ct-right .wpcf7-form input[type=submit]:hover{opacity:.9;transform:translateY(-1px)}.mro-ct-right .wpcf7-form .wpcf7-not-valid-tip{color:#ff6b6b;font-size:.8rem;margin-top:4px;display:block}.mro-ct-right .wpcf7-response-output{border-radius:8px;padding:12px 16px;margin-top:16px;font-size:.85rem}.mro-ct-right .wpcf7-mail-sent-ok{background:rgba(55,213,242,.1);border-color:#37D5F2;color:#37D5F2}@media(max-width:900px){.mro-ct-split{grid-template-columns:1fr;gap:40px;padding:48px 0}}@media(max-width:600px){.mro-ct-right{padding:24px}}</style>
<div class="mro-w">
<div class="mro-bleed mro-hero">
  <div class="mro-hi">
    <span class="mro-eye">Support</span>
    <h1>Get in <em>Touch</em></h1>
    <p>Have a question about your order or our products? Our team is ready to help &mdash; typically within one business day.</p>
    <div class="mro-badge"><code>&#9889; Typical reply within 24 hours</code></div>
  </div>
</div>
<div class="mro-sec">
  <div class="mro-g3">
    <div class="mro-card">
      <div class="mro-ci">&#9993;</div>
      <h3>Email Support</h3>
      <p>Reach us anytime for product or order questions.<br><a href="mailto:support@mrocioa.com">support@mrocioa.com</a></p>
    </div>
    <div class="mro-card">
      <div class="mro-ci">&#9202;</div>
      <h3>Response Time</h3>
      <p>We aim to reply within 24 hours on weekdays. Complex issues may take up to 48 hours.</p>
    </div>
    <div class="mro-card">
      <div class="mro-ci">&#127968;</div>
      <h3>Our Location</h3>
      <p>Shenzhen, China &mdash; shipping professional AV accessories to 50+ countries worldwide.</p>
    </div>
  </div>
</div>
<div class="mro-sec">
  <div class="mro-ct-split">
    <div class="mro-ct-left">
      <span class="mro-eye">Send a Message</span>
      <h2>We&rsquo;d Love to<br>Hear From You</h2>
      <div class="mro-addr-rows">
        <div class="mro-addr-row">
          <div class="mro-addr-ic">&#128205;</div>
          <div><div class="mro-addr-lb">Address</div><div class="mro-addr-vl">619, Building E, Lihao Industrial Park,<br>Longgang, Shenzhen, Guangdong, China 518016</div></div>
        </div>
        <div class="mro-addr-row">
          <div class="mro-addr-ic">&#128222;</div>
          <div><div class="mro-addr-lb">Phone</div><div class="mro-addr-vl">+86 755 8355 1150</div></div>
        </div>
        <div class="mro-addr-row">
          <div class="mro-addr-ic">&#9993;</div>
          <div><div class="mro-addr-lb">Email</div><div class="mro-addr-vl"><a href="mailto:support@mrocioa.com">support@mrocioa.com</a></div></div>
        </div>
      </div>
    </div>
    <div class="mro-ct-right">
      <h4 style="font-family:'Space Grotesk',sans-serif;font-size:1.05rem;font-weight:600;color:#fff;margin:0 0 24px;text-transform:uppercase;letter-spacing:.06em">Contact Us For Any Questions</h4>
      <?php echo do_shortcode('[contact-form-7 id="9498"]'); ?>
    </div>
  </div>
</div>
<div class="mro-bleed mro-cta-band">
  <h2>Browse Our Products</h2>
  <p>HDMI switches, cables, USB-C adapters and more &mdash; professional AV accessories for every setup.</p>
  <div class="mro-cta-btns">
    <a href="/shop/" class="mro-btn mro-btn-w">Shop Now</a>
    <a href="/track-order/" class="mro-btn mro-btn-g">Track Your Order</a>
  </div>
</div>
</div>
    <?php
    return ob_get_clean();
}
add_filter( 'the_content', 'mro_contact_page', 20 );


/* ─────────────────────────────────────────────────────────────
 * C. ABOUT US
 * ─────────────────────────────────────────────────────────────*/
function mro_about_page( $content ) {
    if ( ! is_main_query() || ! in_the_loop() ) return $content;
    if ( ! is_page( [ 'about-us', 'about' ] ) ) return $content;
    if ( strpos( $content, 'mro-w' ) !== false ) return $content;
    ob_start();
    ?>
<div class="mro-w">

<div class="mro-bleed mro-hero">
  <div class="mro-hi">
    <span class="mro-eye">Our Story</span>
    <h1>Powering <em>Every</em> Connection</h1>
    <p class="mro-hero-sub">We&rsquo;re a Shenzhen-based team obsessed with one thing: AV accessories that just work &#8212; every port, every signal, every time.</p>
  </div>
</div>

<div class="mro-bleed mro-stats">
  <div class="mro-si">
    <div>
      <span class="mro-sn">18<em>+</em></span>
      <span class="mro-sl">Products</span>
    </div>
    <div>
      <span class="mro-sn">5<em>+</em></span>
      <span class="mro-sl">Years in AV</span>
    </div>
    <div>
      <span class="mro-sn">50<em>+</em></span>
      <span class="mro-sl">Countries Shipped</span>
    </div>
    <div>
      <span class="mro-sn">1<em>K+</em></span>
      <span class="mro-sl">Happy Customers</span>
    </div>
  </div>
</div>

<div class="mro-sec">
  <div class="mro-g2">
    <div class="mro-story-body">
      <span class="mro-lbl">Who We Are</span>
      <h2 class="mro-h2">Born in Shenzhen.<br>Built for the World.</h2>
      <p>MROCIOA started with a simple frustration: AV accessories that looked fine on paper but failed in the real world. Poor signal integrity, connectors that loosened after weeks, adapters that ran hot.</p>
      <p>We set out to change that. Operating from the heart of Shenzhen&rsquo;s electronics ecosystem, we work directly with manufacturers to ensure every product meets our standards before it ships.</p>
      <p>Today our lineup spans HDMI switches, AV cables, USB-C adapters, and signal splitters &#8212; all tested for the setups our customers actually use.</p>
    </div>
    <div class="mro-story-vis">
      <div class="mro-kv">
        <span class="mro-kv-ico">&#127979;</span>
        <div class="mro-kv-text"><span>Direct Factory Access</span><small>Shenzhen electronics hub</small></div>
      </div>
      <div class="mro-kv">
        <span class="mro-kv-ico">&#128270;</span>
        <div class="mro-kv-text"><span>Signal Integrity Testing</span><small>Every SKU validated</small></div>
      </div>
      <div class="mro-kv">
        <span class="mro-kv-ico">&#127758;</span>
        <div class="mro-kv-text"><span>Global Shipping</span><small>50+ countries delivered</small></div>
      </div>
      <div class="mro-kv">
        <span class="mro-kv-ico">&#127919;</span>
        <div class="mro-kv-text"><span>12-Month Warranty</span><small>On every product</small></div>
      </div>
    </div>
  </div>
</div>

<div class="mro-bleed mro-bg-light">
  <div class="mro-inner-w">
    <div style="text-align:center;margin-bottom:48px">
      <span class="mro-lbl">What We Stand For</span>
      <h2 class="mro-h2">Our Core Principles</h2>
    </div>
    <div class="mro-pillars">
      <div class="mro-pillar">
        <span class="mro-pnum">01</span>
        <h3>Signal Quality First</h3>
        <p>We never cut corners on conductors, shielding, or connectors. If it can&rsquo;t deliver a clean signal, it doesn&rsquo;t make the catalog.</p>
      </div>
      <div class="mro-pillar">
        <span class="mro-pnum">02</span>
        <h3>Universal Compatibility</h3>
        <p>Real-world device testing across TVs, monitors, cameras, laptops, and AV receivers. Our products are built for your actual setup.</p>
      </div>
      <div class="mro-pillar">
        <span class="mro-pnum">03</span>
        <h3>Genuine Support</h3>
        <p>We respond to every inquiry ourselves. No chatbots, no scripted replies &#8212; just real answers from people who know the products.</p>
      </div>
    </div>
  </div>
</div>

<div class="mro-sec">
  <div style="text-align:center;margin-bottom:40px">
    <span class="mro-lbl">What We Make</span>
    <h2 class="mro-h2">Our Product Range</h2>
    <p class="mro-sub">18+ SKUs across four essential AV categories.</p>
  </div>
  <div class="mro-cat-grid">
    <a href="/product-category/av-switch/" class="mro-cat-tile">
      <span class="mro-cat-tile-ico">&#128256;</span>
      <span class="mro-cat-tile-name">AV Switches</span>
      <span class="mro-cat-tile-sub">HDMI &amp; Matrix</span>
    </a>
    <a href="/product-category/av-cables/" class="mro-cat-tile">
      <span class="mro-cat-tile-ico">&#128054;</span>
      <span class="mro-cat-tile-name">AV Cables</span>
      <span class="mro-cat-tile-sub">HDMI &amp; Optical</span>
    </a>
    <a href="/product-category/usb-cables/" class="mro-cat-tile">
      <span class="mro-cat-tile-ico">&#128268;</span>
      <span class="mro-cat-tile-name">USB Cables</span>
      <span class="mro-cat-tile-sub">USB-C &amp; Adapters</span>
    </a>
    <a href="/shop/" class="mro-cat-tile">
      <span class="mro-cat-tile-ico">&#128269;</span>
      <span class="mro-cat-tile-name">View All</span>
      <span class="mro-cat-tile-sub">18+ Products</span>
    </a>
  </div>
</div>

<div class="mro-bleed mro-cta">
  <h2>Ready to Upgrade Your Setup?</h2>
  <p>Professional AV accessories from $13 &#8212; shipped worldwide with a 12-month warranty.</p>
  <a href="/shop/" class="mro-btn mro-btn-w">Shop Now</a>
  <a href="/contact-us/" class="mro-btn mro-btn-g">Contact Us</a>
</div>

</div>
    <?php
    return ob_get_clean();
}
add_filter( 'the_content', 'mro_about_page', 20 );


/* ─────────────────────────────────────────────────────────────
 * D. PRIVACY POLICY
 * ─────────────────────────────────────────────────────────────*/
function mro_privacy_page( $content ) {
    if ( ! is_main_query() || ! in_the_loop() ) return $content;
    if ( ! is_page( [ 'privacy-policy' ] ) ) return $content;
    if ( strpos( $content, 'mro-w' ) !== false ) return $content;
    ob_start();
    ?>
<div class="mro-w">

<div class="mro-bleed mro-hero">
  <div class="mro-hi">
    <span class="mro-eye">Legal</span>
    <h1><em>Privacy</em> Policy</h1>
    <p class="mro-hero-sub">We take your privacy seriously. This policy explains what data we collect, how we use it, and your rights as a customer.</p>
    <span class="mro-pill">&#128274; Last updated: <?php echo esc_html( date( 'F j, Y' ) ); ?></span>
  </div>
</div>

<div class="mro-pol">
  <div class="mro-note"><strong>Summary:</strong> We collect only what is necessary to process your orders and improve your experience. We never sell your personal data to third parties.</div>
  PRIVACY_CONTENT_PLACEHOLDER
</div>

<div class="mro-bleed mro-cta">
  <h2>Questions About Your Data?</h2>
  <p>Contact our support team and we&rsquo;ll respond within 24 hours.</p>
  <a href="/contact-us/" class="mro-btn mro-btn-w">Contact Us</a>
</div>

</div>
    <?php
    return str_replace( 'PRIVACY_CONTENT_PLACEHOLDER', $content, ob_get_clean() );
}
add_filter( 'the_content', 'mro_privacy_page', 20 );


/* ─────────────────────────────────────────────────────────────
 * E. REFUND & RETURNS POLICY
 * ─────────────────────────────────────────────────────────────*/
function mro_refund_page( $content ) {
    if ( ! is_main_query() || ! in_the_loop() ) return $content;
    if ( ! is_page( [
        'refund_returns', 'refund-returns', 'refund-returns-policy',
        'returns-policy', 'refund-policy', 'refund-and-returns-policy',
    ] ) ) return $content;
    if ( strpos( $content, 'mro-w' ) !== false ) return $content;
    ob_start();
    ?>
<div class="mro-w">

<div class="mro-bleed mro-hero">
  <div class="mro-hi">
    <span class="mro-eye">Returns</span>
    <h1>Hassle-Free <em>Returns</em></h1>
    <p class="mro-hero-sub">Not satisfied? We make returns simple. Our 90-day return policy lets you shop with complete confidence.</p>
    <span class="mro-pill">&#9989; 90-Day Return Window</span>
  </div>
</div>

<div class="mro-sec">
  <div style="text-align:center;margin-bottom:40px">
    <span class="mro-lbl">Return Process</span>
    <h2 class="mro-h2">Three Simple Steps</h2>
    <p class="mro-sub">Getting a refund or replacement takes minutes to request.</p>
  </div>
  <div class="mro-steps">
    <div class="mro-step">
      <div class="mro-step-n">1</div>
      <h4>Contact Support</h4>
      <p>Email us with your order number and reason for return. We confirm eligibility within 24 hours.</p>
    </div>
    <div class="mro-step">
      <div class="mro-step-n">2</div>
      <h4>Ship the Item</h4>
      <p>Package the item securely and ship it back. Share the tracking number with our team.</p>
    </div>
    <div class="mro-step">
      <div class="mro-step-n">3</div>
      <h4>Receive Refund</h4>
      <p>Once received and inspected, your refund is processed within 3&#8211;5 business days.</p>
    </div>
  </div>
</div>

<div class="mro-bleed mro-bg-light">
  <div class="mro-inner-w">
    <div class="mro-g3">
      <div class="mro-card">
        <div class="mro-ci">&#128197;</div>
        <h3>90-Day Window</h3>
        <p>Returns accepted within 30 days of delivery. Items must be in original, unused condition with packaging.</p>
      </div>
      <div class="mro-card">
        <div class="mro-ci">&#128176;</div>
        <h3>Full Refund</h3>
        <p>Product cost refunded in full. Original shipping fees are non-refundable unless the item is defective.</p>
      </div>
      <div class="mro-card">
        <div class="mro-ci">&#128204;</div>
        <h3>Defective Items</h3>
        <p>Defective or incorrect items qualify for free return shipping and a full refund or priority replacement.</p>
      </div>
    </div>
  </div>
</div>

<div class="mro-pol">
  REFUND_CONTENT_PLACEHOLDER
</div>

<div class="mro-bleed mro-cta">
  <h2>Need to Start a Return?</h2>
  <p>Contact our team and we&rsquo;ll guide you through the process step by step.</p>
  <a href="/contact-us/" class="mro-btn mro-btn-w">Contact Support</a>
  <a href="/shop/" class="mro-btn mro-btn-g">Continue Shopping</a>
</div>

</div>
    <?php
    return str_replace( 'REFUND_CONTENT_PLACEHOLDER', $content, ob_get_clean() );
}
add_filter( 'the_content', 'mro_refund_page', 20 );


/* ─────────────────────────────────────────────────────────────
 * F. SHIPPING
 * ───────────────────────────────────────────────────────────*/
function mro_shipping_page( $content ) {
    if ( ! is_main_query() || ! in_the_loop() ) return $content;
    if ( ! is_page( [
        'shipping', 'shipping-policy', 'shipping-information', 'delivery-information',
    ] ) ) return $content;
    if ( strpos( $content, 'mro-w' ) !== false ) return $content;
    ob_start();
    ?>
<div class="mro-w">

<div class="mro-bleed mro-hero">
  <div class="mro-hi">
    <span class="mro-eye">Delivery</span>
    <h1>Fast <em>Global</em> Delivery</h1>
    <p class="mro-hero-sub">We ship from Shenzhen to 50+ countries. Every order includes a tracking number so you always know where your package is.</p>
    <span class="mro-pill">&#128230; Tracking included on every order</span>
  </div>
</div>

<div class="mro-sec">
  <span class="mro-lbl">Delivery Options</span>
  <h2 class="mro-h2">Shipping Methods &amp; Times</h2>
  <table class="mro-tbl">
    <thead>
      <tr>
        <th>Method</th>
        <th>Estimated Delivery</th>
        <th>Cost</th>
        <th>Tracking</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>Standard Shipping</strong></td>
        <td>7&#8211;15 business days</td>
        <td>Calculated at checkout</td>
        <td><span class="mro-badge mro-bd-s">Included</span></td>
      </tr>
      <tr>
        <td><strong>Express Shipping</strong></td>
        <td>3&#8211;7 business days</td>
        <td>Calculated at checkout</td>
        <td><span class="mro-badge mro-bd-s">Included</span></td>
      </tr>
      <tr>
        <td><strong>Free Shipping</strong></td>
        <td>10&#8211;18 business days</td>
        <td><span class="mro-badge mro-bd-f">Free</span> on qualifying orders</td>
        <td><span class="mro-badge mro-bd-s">Included</span></td>
      </tr>
    </tbody>
  </table>
  <div class="mro-note"><strong>Note:</strong> Delivery times are estimates and may vary due to customs clearance, local holidays, or carrier delays. International orders may be subject to import duties and local taxes.</div>
</div>

<div class="mro-bleed mro-bg-light">
  <div class="mro-inner-w">
    <div class="mro-g3">
      <div class="mro-card">
        <div class="mro-ci">&#127968;</div>
        <h3>Processing Time</h3>
        <p>Orders processed within 1&#8211;2 business days. Orders placed on weekends or holidays ship on the next working day.</p>
      </div>
      <div class="mro-card">
        <div class="mro-ci">&#127758;</div>
        <h3>50+ Countries</h3>
        <p>We ship worldwide. Delivery times and costs vary by destination and are displayed in full at checkout.</p>
      </div>
      <div class="mro-card">
        <div class="mro-ci">&#128270;</div>
        <h3>Live Tracking</h3>
        <p>You&rsquo;ll receive a tracking number by email once your order ships. Check status anytime on our Track Order page.</p>
      </div>
    </div>
  </div>
</div>

<div class="mro-pol">
  SHIPPING_CONTENT_PLACEHOLDER
</div>

<div class="mro-bleed mro-cta">
  <h2>Questions About Your Delivery?</h2>
  <p>Our support team is here to help with any shipping or tracking inquiry.</p>
  <a href="/contact-us/" class="mro-btn mro-btn-w">Contact Us</a>
  <a href="/track-order/" class="mro-btn mro-btn-g">Track Your Order</a>
</div>

</div>
    <?php
    return str_replace( 'SHIPPING_CONTENT_PLACEHOLDER', $content, ob_get_clean() );
}
add_filter( 'the_content', 'mro_shipping_page', 20 );


/* ─────────────────────────────────────────────────────────────
 * G. TRACK ORDER
 * ─────────────────────────────────────────────────────────────*/
function mro_track_page( $content ) {
    if ( ! is_main_query() || ! in_the_loop() ) return $content;
    if ( ! is_page( [ 'track-order', 'order-tracking', 'track-your-order', 'track' ] ) ) return $content;
    if ( strpos( $content, 'mro-w' ) !== false ) return $content;
    ob_start();
    ?>
<div class="mro-w">

<div class="mro-bleed mro-hero">
  <div class="mro-hi">
    <span class="mro-eye">Order Status</span>
    <h1>Track Your <em>Order</em></h1>
    <p class="mro-hero-sub">Enter your order number and email address to get the latest status on your shipment.</p>
  </div>
</div>

<div class="mro-sec" style="padding-bottom:32px">
  <span class="mro-lbl">Order Journey</span>
  <div class="mro-track-vis">
    <div class="mro-tv-item">
      <div class="mro-tv-dot mro-active">&#9989;</div>
      <span>Order Placed</span>
    </div>
    <div class="mro-tv-item">
      <div class="mro-tv-dot mro-active">&#128230;</div>
      <span>Processing</span>
    </div>
    <div class="mro-tv-item">
      <div class="mro-tv-dot">&#9992;</div>
      <span>Shipped</span>
    </div>
    <div class="mro-tv-item">
      <div class="mro-tv-dot">&#127968;</div>
      <span>Delivered</span>
    </div>
  </div>
</div>

<div class="mro-bleed mro-bg-light">
  <div class="mro-inner">
    <span class="mro-lbl">Enter Details</span>
    <h2 class="mro-h2" style="margin-bottom:28px">Find Your Order</h2>
    <div class="mro-form-box">
      TRACK_FORM_PLACEHOLDER
    </div>
    <p style="font-size:13px;color:#8A96A8;margin-top:16px;line-height:1.6;text-align:center">Can&rsquo;t find your order? <a href="/contact-us/" style="color:#7B6CF6;text-decoration:none;font-weight:500">Contact our support team</a> and we&rsquo;ll look it up for you.</p>
  </div>
</div>

<div class="mro-sec">
  <div class="mro-g3">
    <div class="mro-card">
      <div class="mro-ci">&#9989;</div>
      <h3>Order Confirmed</h3>
      <p>Payment received and order is in our system. Processing typically takes 1&#8211;2 business days before shipping.</p>
    </div>
    <div class="mro-card">
      <div class="mro-ci">&#9992;</div>
      <h3>Order Shipped</h3>
      <p>Your package is on its way. Standard delivery takes 7&#8211;15 business days depending on your destination.</p>
    </div>
    <div class="mro-card">
      <div class="mro-ci">&#63;</div>
      <h3>Need Help?</h3>
      <p>Tracking shows no updates for 7+ days? <a href="/contact-us/">Contact us</a> and we&rsquo;ll investigate with the carrier.</p>
    </div>
  </div>
</div>

<div class="mro-bleed mro-cta">
  <h2>Have a Question About Your Order?</h2>
  <p>Our support team responds within 24 hours on business days.</p>
  <a href="/contact-us/" class="mro-btn mro-btn-w">Contact Support</a>
  <a href="/shop/" class="mro-btn mro-btn-g">Continue Shopping</a>
</div>

</div>
    <?php
    return str_replace( 'TRACK_FORM_PLACEHOLDER', $content, ob_get_clean() );
}
add_filter( 'the_content', 'mro_track_page', 20 );


// ============================================================
// MRO SHOP PAGE — Style A v1
// mro_shop
// ============================================================

function mro_shop_css(){
if(!is_shop())return;
echo '<style id="mro-av-new">';
?>
body.woocommerce-shop .woocommerce-products-header,body.woocommerce-shop .term-description{display:none!important}
body.woocommerce-shop{background:#F6F6FB}
.mro-hero{position:relative;overflow:hidden;background:radial-gradient(120% 120% at 80% 0%,#1c1840,#0C0C16 55%);color:#fff;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-hero:before{content:"";position:absolute;inset:0;background:radial-gradient(600px 400px at 90% 20%,rgba(55,213,242,.22),transparent 60%),radial-gradient(520px 400px at 8% 90%,rgba(123,108,246,.30),transparent 60%);pointer-events:none}
.mro-hero-in{position:relative;z-index:1;max-width:1240px;margin:0 auto;padding:84px 28px 70px;display:grid;grid-template-columns:1.05fr .95fr;gap:40px;align-items:center;min-height:540px}
.mro-eyebrow{display:inline-flex;align-items:center;gap:8px;font-family:'IBM Plex Mono',monospace;font-size:13px;letter-spacing:.16em;text-transform:uppercase;color:#37D5F2;border:1px solid rgba(55,213,242,.35);padding:7px 14px;border-radius:100px;margin-bottom:24px}
.mro-pulse{width:7px;height:7px;border-radius:50%;background:#37D5F2;display:inline-block;animation:mropulse 2s infinite}
@keyframes mropulse{0%{box-shadow:0 0 0 0 rgba(55,213,242,.6)}70%{box-shadow:0 0 0 9px rgba(55,213,242,0)}100%{box-shadow:0 0 0 0 rgba(55,213,242,0)}}
.mro-hero h1{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:clamp(2.6rem,5.4vw,4.4rem);line-height:1.1;letter-spacing:-.01em;color:#fff;margin:0 0 14px}
.mro-grad{background:linear-gradient(95deg,#7B6CF6,#37D5F2);-webkit-background-clip:text;background-clip:text;color:transparent}
.mro-lede{font-size:18px;color:#c7c7da;max-width:480px;margin:0 0 34px}
.mro-ctas{display:flex;gap:14px;flex-wrap:wrap}
.mro-btn-p,.mro-btn-g{display:inline-flex;align-items:center;font-weight:600;font-size:15.5px;padding:15px 28px;border-radius:13px;cursor:pointer;border:none;font-family:'Inter',sans-serif;text-decoration:none;transition:.2s}
.mro-btn-p{background:linear-gradient(95deg,#7B6CF6,#37D5F2);color:#fff;box-shadow:0 14px 34px -10px rgba(123,108,246,.7)}
.mro-btn-g{background:rgba(255,255,255,.06);color:#fff;border:1px solid rgba(255,255,255,.2)}
.mro-trust{display:flex;gap:22px;margin-top:34px;font-size:13.5px;color:#a9a9c2;flex-wrap:wrap;list-style:none;padding:0;margin-left:0}
.mro-trust li{display:inline-flex;align-items:center;gap:7px}
.mro-trust li:before{content:"\2713 ";color:#37D5F2;font-weight:700}
.mro-visual{position:relative;display:grid;place-items:center}
.mro-glow{position:absolute;width:380px;height:380px;background:radial-gradient(circle,rgba(123,108,246,.45),transparent 65%);filter:blur(20px)}
.mro-float{position:relative;width:min(100%,420px);background:#fff;border-radius:28px;padding:16px;box-shadow:0 44px 90px -24px rgba(0,0,0,.6);animation:mrofloat 6s ease-in-out infinite}
@keyframes mrofloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-14px)}}
.mro-float img{display:block;width:100%;border-radius:14px}
.mro-fbadge{position:absolute;top:-3%;right:-3%;z-index:2;background:#fff;color:#0C0C16;font-family:'IBM Plex Mono',monospace;font-weight:500;font-size:12px;padding:8px 13px;border-radius:11px;box-shadow:0 12px 30px rgba(0,0,0,.4);transform:rotate(4deg)}
.mro-fbadge b{color:#7B6CF6}
.mro-stats-bar{background:#15151F;border-top:1px solid rgba(255,255,255,.06);width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-stats-in{max-width:1240px;margin:0 auto;padding:30px 28px;display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.mro-stat{text-align:center;color:#fff;padding:6px}
.mro-stat .n{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:34px;background:linear-gradient(95deg,#7B6CF6,#37D5F2);-webkit-background-clip:text;background-clip:text;color:transparent}
.mro-stat .l{font-size:13px;color:#9a9ab0;font-family:'IBM Plex Mono',monospace;letter-spacing:.04em;margin-top:4px}
.mro-toolbar{background:#F6F6FB;border-bottom:1px solid #E7E7F0;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-toolbar-in{max-width:1240px;margin:0 auto;padding:20px 28px;display:flex;align-items:center;gap:14px;flex-wrap:wrap}
.mro-chip{font-size:14px;font-weight:500;padding:9px 18px;border-radius:100px;background:#fff;border:1px solid #E7E7F0;cursor:pointer;color:#44444f;text-decoration:none;transition:.18s}
.mro-chip.on{background:#0C0C16;color:#fff;border-color:#0C0C16}
body.woocommerce-shop .wd-products{grid-template-columns:repeat(4,1fr)!important}
body.woocommerce-shop .product-grid-item{background:transparent!important;border:none!important;box-shadow:none!important;text-align:center}
body.woocommerce-shop .product-element-top{background:#F1F1F6!important;border-radius:16px!important;aspect-ratio:1/1!important;display:grid!important;place-items:center!important;padding:12px!important;overflow:hidden!important;min-height:0!important}
body.woocommerce-shop .product-element-top img{object-fit:contain!important;width:100%!important;height:100%!important;transition:.35s ease!important}
body.woocommerce-shop .product-grid-item:hover .product-element-top img{transform:scale(1.05)!important}
body.woocommerce-shop .wd-entities-title{font-family:'Inter',sans-serif!important;font-size:15px!important;font-weight:600!important;line-height:1.35!important;text-align:center!important;display:-webkit-box!important;-webkit-line-clamp:2!important;-webkit-box-orient:vertical!important;overflow:hidden!important;min-height:40px!important}
body.woocommerce-shop .price{font-family:'Space Grotesk',sans-serif!important;font-size:20px!important;font-weight:700!important;color:#7B6CF6!important;text-align:center!important;justify-content:center!important}
body.woocommerce-shop .button.add_to_cart_button{background:#0C0C16!important;color:#fff!important;border-radius:100px!important;padding:11px 28px!important;font-weight:600!important;font-size:14px!important;font-family:'Inter',sans-serif!important;border:none!important;margin-top:10px!important;min-width:0!important;min-height:0!important}
body.woocommerce-shop .button.add_to_cart_button:hover{background:#7B6CF6!important}
.mro-vals-wrap{background:#fff;border-top:1px solid #E7E7F0;padding:60px 0;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-vals{max-width:1240px;margin:0 auto;padding:0 28px;display:grid;grid-template-columns:repeat(4,1fr);gap:26px}
.mro-val .ic{width:48px;height:48px;border-radius:13px;background:linear-gradient(135deg,rgba(123,108,246,.12),rgba(55,213,242,.12));display:grid;place-items:center;color:#7B6CF6;margin-bottom:15px}
.mro-val h4{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:16px;margin:0 0 6px;color:#16161F}
.mro-val p{font-size:13.5px;color:#6B6B7B;line-height:1.55;margin:0}
.mro-cta-band{background:linear-gradient(95deg,#7B6CF6,#37D5F2);color:#fff;text-align:center;padding:64px 28px;width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%)}
.mro-cta-band h2{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.1;margin:0 0 12px;color:#fff}
.mro-cta-band p{color:rgba(255,255,255,.88);max-width:520px;margin:0 auto 26px;font-size:16px}
.mro-cta-band a{display:inline-block;background:#fff;color:#0C0C16;font-weight:600;padding:15px 30px;border-radius:13px;text-decoration:none}
@media(max-width:900px){
.mro-hero-in{grid-template-columns:1fr;padding:54px 28px;min-height:auto;text-align:center;gap:30px}
.mro-visual{order:-1}.mro-float{width:280px}
.mro-lede,.mro-ctas,.mro-trust{margin-left:auto;margin-right:auto;justify-content:center}
.mro-stats-in{grid-template-columns:repeat(2,1fr)}
body.woocommerce-shop .wd-products{grid-template-columns:repeat(2,1fr)!important}
.mro-vals{grid-template-columns:repeat(2,1fr)}
}
.woocommerce-shop .elementor-element-7bfc5f7,.woocommerce-shop .elementor-element-3a3ff9a{display:none!important}
<?php
echo '</style>';
}
add_action('wp_head','mro_shop_css');
function mro_shop_before(){
if(!is_shop())return;
?>
<section class="mro-hero"><div class="mro-hero-in"><div><span class="mro-eyebrow"><span class="mro-pulse"></span> Full Product Range</span><h1>All our gear.<br><em class="mro-grad">One store.</em></h1><p class="mro-lede">HDMI switches, cables, adapters and splitters — everything you need to connect your screens, consoles and workspaces.</p><div class="mro-ctas"><a class="mro-btn-p" href="/product/8k-hdmi-switch-5-port-earc/">Shop the S5 Pro &rarr;</a></div><ul class="mro-trust"><li>48Gbps full bandwidth</li><li>Smart EDID / CEC</li><li>12-month warranty</li></ul></div><div class="mro-visual"><div class="mro-glow"></div><div class="mro-float"><span class="mro-fbadge">8K@60Hz &middot; <b>48Gbps</b></span><img src="https://mrocioa.com/wp-content/uploads/2025/08/mrocioa-8k-hdmi-2.1-switch-521arc-600x600.jpg" alt="MROCIOA S5 Pro"></div></div></div></section>
<div class="mro-stats-bar"><div class="mro-stats-in"><div class="mro-stat"><div class="n">18+</div><div class="l">Products</div></div><div class="mro-stat"><div class="n">8K</div><div class="l">HDMI 2.1 ready</div></div><div class="mro-stat"><div class="n">50k+</div><div class="l">Units shipped</div></div><div class="mro-stat"><div class="n">4.8&#9733;</div><div class="l">Avg rating</div></div></div></div>
<div class="mro-toolbar"><div class="mro-toolbar-in"><a class="mro-chip on" href="#">All</a><a class="mro-chip" href="#">HDMI 2.1 / 8K</a><a class="mro-chip" href="#">4K@120Hz</a><a class="mro-chip" href="#">3-in-1</a><a class="mro-chip" href="#">5-in-1</a><a class="mro-chip" href="#">App control</a></div></div>
<?php
}
add_action('woocommerce_before_main_content','mro_shop_before',5);
function mro_shop_after(){
if(!is_shop())return;
?>
<div class="mro-vals-wrap"><div class="mro-vals"><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg></div><h4>48Gbps passthrough</h4><p>Pure hardware path, no compression. Stable 8K@60Hz and 4K@120Hz output.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><h4>Smart EDID / CEC</h4><p>Prevents black screens and handshake drops; CEC lets one remote run all devices.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div><h4>12-month warranty</h4><p>Every switch covered for a full year with responsive support.</p></div><div class="mro-val"><div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div><h4>Worldwide shipping</h4><p>Free over $35, multiple warehouses, full tracking.</p></div></div></div>
<div class="mro-cta-band"><h2>Not sure which one?</h2><p>Tell us how many devices you're connecting and the resolution you need — we'll match you to the right switch in 30 seconds.</p><a href="/contact-us/">Find my switch &rarr;</a></div>
<?php
}
add_action('woocommerce_after_main_content','mro_shop_after',30);

// mro_p13459_iframe_scroll -- removed (scrolling=yes, no JS needed)
function mro_p13459_iframe_scroll() {}
add_action( 'wp_footer', 'mro_p13459_iframe_scroll', 99 );

// mro_embed_mode_init -- hide header/footer/GDPR/adminbar for ?embed=1
function mro_embed_mode_init() {
    if ( ! isset( $_GET['embed'] ) || $_GET['embed'] !== '1' ) return;
    add_filter( 'show_admin_bar', '__return_false' );
    add_action( 'wp_enqueue_scripts', function() {
        wp_dequeue_style( 'cky-style' );
        wp_dequeue_script( 'cookie-law-info-js' );
        wp_dequeue_script( 'cookie-law-info' );
        wp_dequeue_script( 'cky-script' );
    }, 999 );
    add_action( 'wp_head', function() {
        echo '<style>.wd-header-wrap,.wd-footer-area,.wd-footer-container,#wpadminbar,.xts-page-title,.breadcrumbs,#cky-consent-container,#cky-consent-bar,.cky-consent-container,.cky-overlay,.cky-revisit-btn{display:none!important}html{margin-top:0!important}body{margin:0;padding:0;overflow-x:hidden}.vx-wrap>*:not(.vx-stage){display:none!important}.vx-stage{margin-top:0!important;padding-top:0!important}</style>';
    }, 999 );
}
add_action( 'init', 'mro_embed_mode_init' );

// mro_blog_single_css -- hide WoodMart page title banner on single posts
add_action( 'wp_head', function() {
    if ( ! is_singular( 'post' ) ) return;
    echo '<style>.wd-page-title{display:none!important}.wd-page-content{padding-top:0!important}.wd-single-post-header{display:none!important}main.wd-content-layout{padding-top:0!important}h1.wd-post-title,h1.wd-entities-title{display:none!important}.single-post .comment-form #submit,.single-post .form-submit input[type=submit]{background:#7B6CF6!important;color:#fff!important;border:none!important;border-radius:999px!important;padding:12px 28px!important;font-family:"Space Grotesk",sans-serif!important;font-weight:600!important;font-size:14px!important;letter-spacing:.02em!important;cursor:pointer!important;transition:opacity .2s!important}.single-post .comment-form #submit:hover{opacity:.85!important}.single-post #comments h2,.single-post .comments-title{font-family:"Space Grotesk",sans-serif;font-weight:600;color:#0B0E14}.single-post #comments{padding-bottom:40px}.single-post .comment-form{padding-bottom:32px}</style>';
}, 20 );

// mro_blog_single_css -- hide WoodMart page title banner on single posts
add_action( 'wp_head', function() {
    if ( ! is_singular( 'post' ) ) return;
    echo '<style>.wd-page-title{display:none!important}.wd-page-content{padding-top:0!important}.wd-single-post-header{display:none!important}main.wd-content-layout{padding-top:0!important}h1.wd-post-title,h1.wd-entities-title{display:none!important}.single-post .comment-form #submit,.single-post .form-submit input[type=submit]{background:#7B6CF6!important;color:#fff!important;border:none!important;border-radius:999px!important;padding:12px 28px!important;font-family:"Space Grotesk",sans-serif!important;font-weight:600!important;font-size:14px!important;letter-spacing:.02em!important;cursor:pointer!important;transition:opacity .2s!important}.single-post .comment-form #submit:hover{opacity:.85!important}.single-post #comments h2,.single-post .comments-title{font-family:"Space Grotesk",sans-serif;font-weight:600;color:#0B0E14}.single-post #comments{padding-bottom:40px}.single-post .comment-form{padding-bottom:32px}</style>';
}, 20 );


function mro_blog_latest_articles_html( $exclude_id = 0, $limit = 5 ) {
    $recent = get_posts( [
        'numberposts'  => (int) $limit,
        'post__not_in' => array_filter( [ (int) $exclude_id ] ),
        'post_status'  => 'publish',
    ] );
    $list_html = '';
    foreach ( $recent as $rp ) {
        $list_html .= sprintf(
            '<li class="mro-bh-li"><a href="%s"><span class="mro-bh-li-t">%s</span><span class="mro-bh-li-d">%s &rsaquo;</span></a></li>',
            esc_url( get_permalink( $rp ) ),
            esc_html( get_the_title( $rp ) ),
            esc_html( get_the_date( 'M j', $rp ) )
        );
    }
    return $list_html;
}

// mro_blog_post_hero -- inject hero section for blog single posts (2-col layout)
function mro_blog_post_hero( $query = null ) {
    if ( $query && ! $query->is_main_query() ) return;
    if ( ! is_singular( 'post' ) ) return;
    global $post;
    $title     = get_the_title();
    $cat_links = get_the_category_list( ', ' );
    $date      = get_the_date( 'F j, Y' );
    $list_html = mro_blog_latest_articles_html( $post->ID );
    echo '<style>
.mro-blog-hero{background:linear-gradient(160deg,#0d1829 0%,#0B0E14 55%,#100818 100%);margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);width:100vw;position:relative;overflow:hidden}
.mro-blog-hero::before{content:"";position:absolute;inset:0;background:radial-gradient(ellipse 80% 100% at 20% 120%,rgba(55,213,242,.10) 0%,transparent 65%);pointer-events:none}
.mro-bh-inner{display:grid;grid-template-columns:1fr 1fr;gap:0;max-width:1400px;margin:0 auto;padding:72px 48px 64px}
.mro-bh-left{display:flex;flex-direction:column;justify-content:center;padding-right:48px;border-right:1px solid rgba(255,255,255,.08)}
.mro-bh-eye{display:inline-flex;align-items:center;gap:8px;font-family:"IBM Plex Mono",monospace;font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:#37D5F2;margin-bottom:20px}
.mro-bh-eye::before{content:"";width:6px;height:6px;border-radius:50%;background:#37D5F2;flex-shrink:0}
.mro-bh-title{font-family:"Space Grotesk",sans-serif;font-weight:600;font-size:clamp(26px,3.5vw,48px);line-height:1.15;letter-spacing:-.02em;color:#fff;margin:0 0 20px}
.mro-bh-meta{font-family:"IBM Plex Mono",monospace;font-size:12px;color:rgba(255,255,255,.4);letter-spacing:.06em}
.mro-bh-meta a{color:rgba(55,213,242,.7);text-decoration:none}
.mro-bh-right{padding-left:48px;display:flex;flex-direction:column;justify-content:center}
.mro-bh-rtitle{font-family:"IBM Plex Mono",monospace;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:rgba(255,255,255,.3);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid rgba(255,255,255,.08)}
.mro-bh-list{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:4px}
.mro-bh-li a{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.06);text-decoration:none;transition:border-color .2s}
.mro-bh-li a:hover{border-bottom-color:rgba(55,213,242,.3)}
.mro-bh-li-t{font-family:"Inter",sans-serif;font-size:14px;color:rgba(255,255,255,.75);line-height:1.4;flex:1;transition:color .2s}
.mro-bh-li a:hover .mro-bh-li-t{color:#fff}
.mro-bh-li-d{font-family:"IBM Plex Mono",monospace;font-size:11px;color:#37D5F2;white-space:nowrap;flex-shrink:0}
.wd-single-post-header{display:none!important}
.wd-page-content{padding-top:0!important}
@media(max-width:768px){.mro-bh-inner{grid-template-columns:1fr;gap:0;padding:48px 24px 42px}.mro-bh-left{padding-right:0;border-right:none;border-bottom:none;padding-bottom:0}.mro-bh-right{display:none!important}}
</style>
<div class="mro-blog-hero">
  <div class="mro-bh-inner">
    <div class="mro-bh-left">
      <span class="mro-bh-eye">MROCIOA BLOG</span>
      <h1 class="mro-bh-title">' . esc_html( $title ) . '</h1>
      <div class="mro-bh-meta">' . $cat_links . ' &nbsp;&middot;&nbsp; ' . esc_html( $date ) . '</div>
    </div>
    <div class="mro-bh-right">
      <div class="mro-bh-rtitle">Latest Articles</div>
      <ul class="mro-bh-list">' . $list_html . '</ul>
    </div>
  </div>
</div>
';
}
add_action( 'loop_start', 'mro_blog_post_hero', 5 );


// mro_blog_post_cta -- inject blue CTA zone at bottom of blog single posts
function mro_blog_post_cta( $query = null ) {
    if ( $query && ! $query->is_main_query() ) return;
    if ( ! is_singular( 'post' ) ) return;
    global $post;
    $latest_html = $post instanceof WP_Post ? mro_blog_latest_articles_html( $post->ID ) : '';
    echo '<style>
.mro-blog-latest-mobile{display:none}
.mro-blog-cta{margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);width:100vw;padding:80px 40px;background:linear-gradient(135deg,#37D5F2 0%,#7B6CF6 100%);text-align:center}
.mro-blog-cta h2{font-family:"Space Grotesk",sans-serif;font-weight:600;font-size:clamp(24px,3.5vw,42px);color:#0B0E14;margin:0 0 16px;letter-spacing:-.02em}
.mro-blog-cta p{font-size:17px;color:rgba(11,14,20,.75);margin:0 auto 32px;max-width:560px;line-height:1.65}
.mro-blog-cta a{display:inline-block;background:#0B0E14;color:#fff;font-family:"Space Grotesk",sans-serif;font-weight:600;font-size:15px;letter-spacing:.02em;padding:14px 32px;border-radius:999px;text-decoration:none;transition:opacity .2s}
.mro-blog-cta a:hover{opacity:.85}
@media(max-width:768px){.mro-blog-latest-mobile{display:block;padding:40px 24px 8px;background:#fff}.mro-blog-latest-mobile .mro-bh-rtitle{color:#8A96A8;border-bottom:1px solid #E4E8F0}.mro-blog-latest-mobile .mro-bh-li a{border-bottom:1px solid #E4E8F0}.mro-blog-latest-mobile .mro-bh-li-t{color:#1A2030}.mro-blog-latest-mobile .mro-bh-li-d{color:#7B6CF6}.mro-blog-cta{padding:56px 24px}.single-post .entry-content,.single-post .wd-entry-content{font-size:16px;line-height:1.7;overflow-wrap:anywhere;word-break:break-word}}
</style>
' . ( $latest_html ? '<div class="mro-blog-latest-mobile"><div class="mro-bh-rtitle">Latest Articles</div><ul class="mro-bh-list">' . $latest_html . '</ul></div>' : '' ) . '
<div class="mro-blog-cta">
  <h2>Not sure which switch is right for you?</h2>
  <p>Tell us how many devices you\'re connecting and the resolution you need &mdash; we\'ll match you to the right switch in 30 seconds.</p>
  <a href="/contact-us/">Find my switch &rarr;</a>
</div>
';
}
add_action( 'loop_end', 'mro_blog_post_cta', 30 );


// mro_blog_demo_height -- dynamic iframe height: viewport minus sticky nav
// nav=90px for public, +32px admin bar when showing
function mro_blog_demo_height() {
    if ( ! is_singular( 'post' ) ) return;
    $nav_h = is_admin_bar_showing() ? 122 : 90;
    echo '<style>
.mro-demo-fs{height:calc(100vh - ' . $nav_h . 'px)!important}
.mro-demo-fs iframe{height:100%!important}
@supports(height:100svh){
  .mro-demo-fs{height:calc(100svh - ' . $nav_h . 'px)!important}
}
</style>';
}
add_action( 'wp_head', 'mro_blog_demo_height', 25 );

function mro_mobile_table_labels() {
    if ( ! is_singular( 'post' ) && ! is_page( [
        'shipping',
        'shipping-policy',
        'shipping-information',
        'delivery-information',
    ] ) ) return;
    echo '<script>(function(){
document.querySelectorAll(".single-post .entry-content table,.single-post .wd-entry-content table,.mro-tbl").forEach(function(table){
  var heads=[].slice.call(table.querySelectorAll("thead th")).map(function(th){return (th.textContent||"").replace(/\s+/g," ").trim()});
  if(!heads.length)return;
  table.classList.add("mro-card-table");
  table.querySelectorAll("tbody tr").forEach(function(row){
    [].slice.call(row.children).forEach(function(cell,i){
      if(heads[i]&&!cell.getAttribute("data-label"))cell.setAttribute("data-label",heads[i]);
    });
  });
});
})();</script>' . "\n";
}
add_action( 'wp_footer', 'mro_mobile_table_labels', 35 );

// === MRO PERFORMANCE: Dequeue bloat (2026-06-15) ===
add_action( 'wp_enqueue_scripts', 'mro_dequeue_bloat', 99 );
function mro_dequeue_bloat() {
    $wp_blocks = [
        'wp-redux-routine','wp-deprecated','wp-priority-queue',
        'wp-compose','wp-undo-manager','react-jsx-runtime',
        'wp-keycodes','wp-is-shallow-equal','wp-escape-html',
        'wp-private-apis','wp-data','wp-element','react',
        'wp-dom','wp-i18n','wp-hooks','react-dom',
    ];
    foreach ( $wp_blocks as $h ) { wp_dequeue_script( $h ); }
    if ( ! is_page( 'contact' ) ) {
        wp_dequeue_script( 'contact-form-7' );
        wp_dequeue_script( 'swv' );
        wp_dequeue_style( 'contact-form-7' );
    }
    if ( ! is_checkout() && ! is_account_page() ) {
        wp_dequeue_script( 'simple-cloudflare-turnstile' );
        wp_dequeue_script( 'simple-cloudflare-turnstile-woocommerce' );
    }
    wp_dequeue_style( 'ppcp-local-alternative-payment-methods' );
}

// === MRO CLS FIX: Product gallery aspect-ratio (2026-06-15) ===
add_action( 'wp_head', 'mro_fix_product_gallery_cls', 5 );
function mro_fix_product_gallery_cls() {
    if ( ! is_product() ) return;
    echo '<style>.woocommerce-product-gallery .woocommerce-product-gallery__image{aspect-ratio:1/1;overflow:hidden}.woocommerce-product-gallery .woocommerce-product-gallery__image img{width:100%;height:100%;object-fit:contain}.flex-control-thumbs li{aspect-ratio:1/1;overflow:hidden}.flex-control-thumbs li img{width:100%;height:100%;object-fit:cover}.woocommerce-product-gallery__wrapper{min-height:300px}</style>' . "\n";
}

// === MRO LCP: Preload + eager load main product image (2026-06-15) ===
add_action( 'wp_head', 'mro_preload_product_lcp', 1 );
function mro_preload_product_lcp() {
    if ( ! is_product() ) return;
    global $post;
    $product = wc_get_product( $post->ID );
    if ( ! $product ) return;
    $img_id = $product->get_image_id();
    if ( ! $img_id ) return;
    $src = wp_get_attachment_image_src( $img_id, 'woocommerce_single' );
    if ( empty( $src[0] ) ) return;
    echo '<link rel="preload" as="image" href="' . esc_url( $src[0] ) . '" fetchpriority="high">' . "\n";
}

add_filter( 'wp_get_attachment_image_attributes', 'mro_eager_product_main_image', 99, 3 );
function mro_eager_product_main_image( $attr, $attachment, $size ) {
    if ( ! is_product() ) return $attr;
    if ( $size === 'woocommerce_single' ) {
        $attr['loading']='eager'; $attr['fetchpriority']='high'; $attr['decoding']='sync';
        if(isset($attr['src'])) $attr['src']=preg_replace('/\.(jpe?g|png)(\?|$)/i','.webp$2',$attr['src']);
        $ss=wp_get_attachment_image_srcset($attachment->ID,$size);
        if($ss){$attr['srcset']=preg_replace('/\.(jpe?g|png)(\s)/i','.webp$2',$ss);$attr['sizes']='(max-width:768px) 100vw, 740px';}
        if(isset($attr['class'])){$attr['class']=str_replace('lazy-loading','',$attr['class']);$attr['class']=trim(preg_replace('/\s+/',' ',$attr['class']));}
        unset($attr['data-lazy-src'],$attr['data-srcset']);
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes','mro_remove_logo_fetchpriority',100,3);
function mro_remove_logo_fetchpriority($attr,$attachment,$size){
    if(isset($attr['fetchpriority'])&&$attr['fetchpriority']==='high'){
        if(!is_product()||$size!=='woocommerce_single') unset($attr['fetchpriority']);
    }
    return $attr;
}
// 移除 logo 的 fetchpriority="high"，避免和 LCP 主图抢带宽
add_filter( 'wp_get_attachment_image', 'mro_remove_logo_fp', 999, 3 );
function mro_remove_logo_fp( $html, $attachment_id, $size ) {
    if ( is_product() && $size !== 'woocommerce_single' ) {
        $html = str_replace( ' fetchpriority="high"', '', $html );
    }
    return $html;
}

// ── Google Tag Manager ──────────────────────────────────────────────────
function mrocioa_gtm_head() { ?>
<script data-cfasync="false">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-T5MW49C');</script>
<?php }
add_action( 'wp_head', 'mrocioa_gtm_head', 1 );

function mrocioa_gtm_body() { ?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T5MW49C" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php }
add_action( 'wp_body_open', 'mrocioa_gtm_body', 1 );
// === MRO Gallery v4: track+swipe (2026-06-16) ===
add_action( 'wp_head', 'mro_gallery_styles', 1 );
function mro_gallery_styles() {
    if ( ! is_product() ) return;
    echo '<style>#mro-gallery{display:flex!important;flex-direction:column!important;gap:12px!important;width:100%!important;max-width:100%!important;overflow:hidden!important}#mro-main{width:100%!important;aspect-ratio:1!important;background:#f6f7f9!important;border-radius:16px!important;overflow:hidden!important;position:relative!important;cursor:grab}#mro-main.dragging{cursor:grabbing}#mro-track{display:block!important;width:100%!important;height:100%!important;position:relative!important;overflow:hidden!important;transform:none!important;transition:none!important;will-change:auto!important}#mro-gallery .mro-gs{display:none!important;width:100%!important;height:100%!important;max-width:100%!important;object-fit:contain!important;-webkit-user-drag:none;user-select:none}#mro-gallery .mro-gs.is-a{display:block!important}#mro-gallery .mro-thumb-nav{display:flex!important;align-items:center!important;gap:6px!important;max-width:100%!important;overflow:hidden!important}#mro-thumbs{display:flex!important;gap:8px!important;overflow:hidden!important;flex:1!important;min-width:0!important}#mro-gallery .mro-gt{display:block!important;width:80px!important;height:80px!important;min-width:80px!important;flex-shrink:0!important;border:2px solid #e2e2e2!important;border-radius:8px!important;overflow:hidden!important;cursor:pointer!important;padding:0!important;background:#f6f7f9!important;transition:border-color .2s!important}#mro-gallery .mro-gt.is-a,#mro-gallery .mro-gt:hover{border-color:#7B6CF6!important}#mro-gallery .mro-gt img{width:100%!important;height:100%!important;object-fit:cover!important;display:block!important}#mro-gallery .mro-tarrow{flex-shrink:0;width:34px;height:34px;border-radius:50%;background:#fff;border:1.5px solid #e2e2e2;cursor:pointer;font-size:22px;line-height:32px;text-align:center;color:#0b0e14;transition:border-color .2s;padding:0}#mro-gallery .mro-tarrow:hover{border-color:#7B6CF6}#mro-gallery .mro-tarrow[hidden]{display:none!important}</style>' . "\n";
}

add_action( 'wp_footer', 'mro_gallery_script' );
function mro_gallery_script() {
    if ( ! is_product() ) return;
    echo '<script>(function(){
var g=document.getElementById("mro-gallery");if(!g)return;
var track=document.getElementById("mro-track"),main=document.getElementById("mro-main"),tc=document.getElementById("mro-thumbs");
var th=g.querySelectorAll(".mro-gt"),tp=g.querySelector(".mro-tarrow--prev"),tn=g.querySelector(".mro-tarrow--next");
var slides=g.querySelectorAll(".mro-gs");
var n=th.length,cur=0;
function go(i){
  cur=Math.max(0,Math.min(n-1,i));
  if(track)track.style.transform="none";
  slides.forEach(function(s,j){s.classList.toggle("is-a",j===cur)});
  th.forEach(function(t,j){t.classList.toggle("is-a",j===cur);t.setAttribute("aria-selected",j===cur?"true":"false")});
  if(th[cur])th[cur].scrollIntoView({behavior:"smooth",block:"nearest",inline:"nearest"});
  utav();
}
function utav(){if(!tc)return;if(tp)tp.hidden=(tc.scrollLeft<=1);if(tn)tn.hidden=(tc.scrollLeft+tc.clientWidth>=tc.scrollWidth-1)}
if(tp)tp.addEventListener("click",function(){tc.scrollBy({left:-176,behavior:"smooth"})});
if(tn)tn.addEventListener("click",function(){tc.scrollBy({left:176,behavior:"smooth"})});
if(tc)tc.addEventListener("scroll",utav);
th.forEach(function(t,i){t.addEventListener("click",function(){go(i)})});
var sx=0,sy=0,st=0,dir=null;
if(main){
  main.addEventListener("touchstart",function(e){sx=e.touches[0].clientX;sy=e.touches[0].clientY;st=-cur*100;dir=null;},{passive:true});
  main.addEventListener("touchmove",function(e){
    var dx=e.touches[0].clientX-sx,dy=e.touches[0].clientY-sy;
    if(dir===null&&(Math.abs(dx)>6||Math.abs(dy)>6))dir=Math.abs(dx)>Math.abs(dy)?"h":"v";
    if(dir==="h")e.preventDefault();
  },{passive:false});
  main.addEventListener("touchend",function(e){
    if(dir!=="h")return;
    var dx=e.changedTouches[0].clientX-sx,thr=main.offsetWidth*0.25;
    if(dx<-thr&&cur<n-1)go(cur+1);else if(dx>thr&&cur>0)go(cur-1);else go(cur);
  });
}
utav();
})();</script>' . "\n";
}

// === MRO PERF v2: Script cleanup + fonts (2026-06-16) ===


add_filter( 'script_loader_tag', 'mro_async_cookieyes', 10, 3 );
function mro_async_cookieyes( $tag, $handle, $src ) {
    if ( $handle === 'cookie-law-info' )
        return str_replace( '<script ', '<script defer ', $tag );
    return $tag;
}

add_filter( 'style_loader_src', 'mro_gfonts_display_swap', 10, 2 );
function mro_gfonts_display_swap( $src, $handle ) {
    if ( strpos( $src, 'fonts.googleapis.com' ) !== false && strpos( $src, 'display=' ) === false )
        $src = add_query_arg( 'display', 'swap', $src );
    return $src;
}



// ── End GTM ───────────────────────────────────────────────────────────
// === PERF v2: Remove RML + Gutenberg JS from frontend ===
add_action( 'wp_enqueue_scripts', function() {
    if ( is_admin() ) return;
    foreach ( [
        'real-media-library-lite-rml',
        'devowl-wp-real-utils-helper',
        'devowl-wp-real-product-manager-wp-client',
        'vendor-devowl-wp-real-product-manager-wp-client',
        'devowl-wp-utils',
        'vendor-devowl-wp-utils',
        'mobx',
    ] as $h ) { wp_dequeue_script( $h ); wp_deregister_script( $h ); }
    foreach ( [
        'real-media-library-lite-rml',
        'devowl-wp-real-product-manager-wp-client',
        'rml-font',
    ] as $h ) { wp_dequeue_style( $h ); wp_deregister_style( $h ); }
    foreach ( [
        'wp-block-editor','wp-blocks','wp-components','wp-core-data',
        'wp-rich-text','wp-data','wp-element','wp-api-fetch','wp-url',
        'wp-hooks','wp-private-apis','wp-redux-routine','wp-preferences',
        'wp-preferences-persistence','wp-notices','wp-commands',
        'wp-keyboard-shortcuts','wp-autop',
        'wp-block-serialization-default-parser','wp-primitives',
        'wp-html-entities','wp-date','moment','wp-compose',
        'wp-undo-manager','wp-priority-queue','wp-keycodes',
        'wp-is-shallow-equal','wp-escape-html','wp-dom','wp-deprecated',
        'wp-blob','wp-a11y','wp-token-list','wp-theme','wp-style-engine',
        'wp-upload-media','wp-media-utils',
        'react','react-dom','react-jsx-runtime','wp-polyfill',
        'media-upload','media-views','media-editor','media-audiovideo',
        'thickbox','wp-plupload','plupload','moxiejs','wp-pointer',
    ] as $h ) { wp_dequeue_script( $h ); }
}, 100 );

// === PERF v2: iframe lazy loading ===
add_filter( 'the_content', function( $c ) {
    return preg_replace( '/<iframe(?![^>]*loading=)/i', '<iframe loading="lazy"', $c );
}, 20 );

// === WC Session: suppress cookie on homepage/shop when cart is empty ===
add_filter( 'woocommerce_should_set_session_cookie', function( $start ) {
    if ( is_front_page() || is_shop() || is_product_category() ) {
        if ( function_exists('WC') && WC()->cart && WC()->cart->is_empty() ) {
            return false;
        }
    }
    return $start;
} );
