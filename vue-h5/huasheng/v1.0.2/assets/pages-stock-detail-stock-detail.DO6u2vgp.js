import{R as e,V as t,aa as a,ad as l,x as o,y as s,D as i,H as n,Y as r,A as u,F as c,q as d,C as f,Z as p,I as m,J as y,B as _,L as v,M as g,z as x,G as h,E as b,ag as w,ai as k,aj as F}from"./index-BpNRNyt1.js";import{_ as D}from"./uni-nav-bar.DzQ3GIPc.js";import{r as L}from"./uni-app.es.DOVXrixg.js";import{_ as S,a as $}from"./u-row.BX7H0h2n.js";import{_ as A}from"./u-skeleton.C1ZdLD0X.js";import{_ as C}from"./u-tabs.CHBkNVWk.js";import{_ as j}from"./l-echart.BbciLGNL.js";import{_ as T}from"./u-empty.DrFzq4Ux.js";import{_ as M}from"./u-subsection.b6Z9YTN9.js";import{_ as I}from"./u-button.gilaSs3u.js";import{n as N,o as z,p as O,q as R,r as P,s as K,t as G,u as Z}from"./stock.BWxM4DIu.js";import{L as q,e as E}from"./index.BLMtQXR6.js";import{_ as W}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.BdLyHM4-.js";import"./uni-status-bar.BKUrsIDI.js";import"./u-icon.Bew-d3Qt.js";import"./u-loading-icon.ByX6rnJ-.js";const B=W({__name:"stock-detail",props:{name:String,code:String},setup(W){const{t:B}=e(),H=W,V=t(!1),J=t({}),Q=[{name:B("stockDetail.text.timeSharing")},{name:B("stockDetail.text.kLine")},{name:B("stockDetail.text.information")}],Y=[{name:B("stockDetail.text.dailyK")},{name:B("stockDetail.text.weekK")},{name:B("stockDetail.text.monthK")}],U=t(0),X=t(0),ee=t(),te=t(),ae=t(),le=t(),oe=a(),se=a(),ie=a(),ne=a();let re,ue,ce,de;const fe=t(!0),pe=t(!0),me=t(!0),ye=t(!0),_e=t(!0),ve=t(!0),ge=t(!1),xe=t(!1),he=t(!1),be=t(!1),we=a([]);let ke,Fe,De,Le;async function Se(){const e=await Ae(O);if(!e.length)return ge.value=!0,void(pe.value=!1);const t=[],a=[],l=[];for(const i of e)t.push(i.time.substr(0,2)+":"+i.time.substr(2,2)),a.push(parseFloat(i.price)),l.push(i.volume);const o=function(){let e=[];return["09","10","11","13","14"].forEach((t=>{if("11"===t)for(let a=0;a<30;a++){let l=t+":"+(a<10?"0"+a:a);e.push(l)}else if("09"===t)for(let a=30;a<60;a++){let l=t+":"+a;e.push(l)}else for(let a=0;a<60;a++){let l=t+":"+(a<10?"0"+a:a);e.push(l)}})),e.push("15:00"),e}(),s={tooltip:{trigger:"axis",axisPointer:{type:"cross"},backgroundColor:"#fff",borderWidth:1,borderColor:"#e2e2e2",padding:10,textStyle:{color:"#000"},position:(e,t,a,l,o)=>{var s={top:10};return s[["left","right"][+(e[0]<o.viewSize[0]/2)]]=30,s}},grid:[{left:30,top:16,height:300},{left:0,right:0,height:40,top:340}],xAxis:[{type:"category",splitNumber:1,boundaryGap:!0,data:o,max:241,axisLine:{show:!1},axisTick:{show:!1},axisLabel:{show:!0,color:"#999",fontSize:9,interval:0,formatter:(e,t)=>0===t?"9:30":60===t?"10:30":120===t?"11:30/13:00":180===t?"14:00":240===t?"15:00":void 0}},{type:"category",gridIndex:1,data:o,max:241,scale:!0,boundaryGap:!1,axisLine:{lineStyle:{color:"#b5b5b5",width:1}},axisTick:{show:!1},splitLine:{show:!1},axisLabel:{show:!1},splitNumber:20}],yAxis:[{scale:!0,splitNumber:3,axisLabel:{color:"#999",formatter:"{value}\n"}},{scale:!0,show:!1,gridIndex:1,splitNumber:2,axisLabel:{show:!1},axisLine:{show:!1,lineStyle:{color:"#e2e2e2",width:1}},axisTick:{show:!1},splitLine:{show:!1}}],series:[{name:B("stockDetail.text.price"),type:"line",smooth:!0,symbol:"none",sampling:"average",itemStyle:{color:"#f25d23"},lineStyle:{color:"#f25d23",width:1.2,pacity:.9,type:"solid"},max:241,areaStyle:{color:new q(0,0,0,1,[{offset:0,color:"rgba(213,220,245,0.8)"},{offset:1,color:"rgba(223,231,255,0.8)"}]),opacity:1,origin:"start"},data:a},{name:B("stockDetail.text.price"),type:"bar",xAxisIndex:1,yAxisIndex:1,itemStyle:{color:"#f25d23",margin:100},data:l}]};pe.value=!1,re=oe.value=s}const $e=(e,t)=>{const a=[];for(let l=0,o=t.length;l<o;l++){if(l<e){a.push("-");continue}let o=0;for(let a=0;a<e;a++)o+=t[l-a][1];a.push(+(o/e).toFixed(3))}return a};async function Ae(e){const{error:t,message:a,data:l}=await e({code:H.code});return t?(await d({title:a,icon:"none"}),[]):l}async function Ce(){const e=await Ae(K);if(!e.length)return xe.value=!0,void(me.value=!1);me.value=!1,ue=se.value=await je(e)}async function je(e,t=80){const a=[],l=[],o=[];for(let s=0;s<e.length;s++){const t=e[s];a.push(t.time),l.push([parseFloat(t.open),parseFloat(t.close),parseFloat(t.low),parseFloat(t.high),parseFloat(t.volume)]),o.push([s,parseFloat(t.volume),parseFloat(t.open)>parseFloat(t.close)?1:-1])}return{dataZoom:[{type:"inside",start:t,end:100},{show:!0,type:"slider",top:"90%",start:50,end:100}],backgroundColor:"#fff",animation:!1,legend:{left:"center",data:["MA5","MA10","MA20","MA30"]},tooltip:{trigger:"axis",axisPointer:{type:"cross"},backgroundColor:"rgba(245, 245, 245, 0.8)",borderWidth:1,borderColor:"#ccc",padding:10,textStyle:{color:"#000"},position:(e,t,a,l,o)=>{var s={top:6};return s[["left","right"][+(e[0]<o.viewSize[0]/2)]]=10,s}},axisPointer:{link:{xAxisIndex:"all"},label:{backgroundColor:"#777"}},visualMap:{show:!1,seriesIndex:5,dimension:2,pieces:[{value:1,color:"#00da3c"},{value:-1,color:"#ec0000"}]},grid:[{top:"10%",left:"3%",right:"2%",height:"60%"},{left:"3%",right:"2%",top:"78%",height:"11%"}],xAxis:[{type:"category",data:a,scale:!0,boundaryGap:!1,axisLine:{onZero:!1,lineStyle:{color:"#e2e2e2"}},splitLine:{show:!1,lineStyle:{color:"#333"}},axisLabel:{color:"#333",fontSize:11,align:"center"},splitNumber:20,min:"dataMin",max:"dataMax",axisPointer:{z:100}},{type:"category",gridIndex:1,data:a,scale:!0,boundaryGap:!1,axisLine:{onZero:!1},axisTick:{show:!1},splitLine:{show:!1},axisLabel:{show:!1},splitNumber:20,min:"dataMin",max:"dataMax"}],yAxis:[{scale:!0,splitNumber:3,axisLine:{lineStyle:{color:"#e2e2e2"}},splitLine:{show:!0,lineStyle:{color:"#e2e2e2"}},axisTick:{show:!1},axisLabel:{inside:!0,color:"#333",formatter:"{value}\n"}},{scale:!0,gridIndex:1,splitNumber:2,axisLabel:{show:!1},axisLine:{show:!1},axisTick:{show:!1},splitLine:{show:!1}}],series:[{name:B("stockDetail.text.kLineInformation"),type:"candlestick",data:l,itemStyle:{normal:{color:"#ec0000",color0:"#00da3c",borderColor:null,borderColor0:null}},tooltip:{axisPointer:{type:"cross"},trigger:"axis",formatter:e=>e.name+"<br>"+B("stockDetail.text.opening")+e.value[1]+"<br>"+B("stockDetail.text.closing")+e.value[2]+"<br>"+B("stockDetail.text.lowest")+e.value[3]+"<br>"+B("stockDetail.text.highest")+e.value[4]}},{name:"MA5",type:"line",data:$e(5,l),symbol:"none",smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:"MA10",type:"line",data:$e(10,l),symbol:"none",smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:"MA20",type:"line",data:$e(20,l),symbol:"none",smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:"MA30",type:"line",data:$e(30,l),symbol:"none",smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:B("stockDetail.text.tradingVolume"),type:"bar",xAxisIndex:1,yAxisIndex:1,data:o}]}}async function Te(){const{error:e,message:t}=await R({code:H.code,name:H.name});await d({title:t,icon:e?"none":"success"}),V.value=!0}async function Me(){const{error:e,message:t}=await P({code:H.code,name:H.name});await d({title:t,icon:e?"none":"success"}),V.value=!1}return async function(){J.value=await Ae(z),fe.value=!1}(),Se(),async function(){const{error:e,message:t,data:a}=await N({code:H.code});e?await d({title:t,icon:"none"}):V.value=!!a}(),l(U,(e=>{if(0!==e||ke)if(1!==e||Fe)2===e&&async function(){var e,t;const a=await F({url:"https://data.001ghsp.com/stock/notice",method:"POST",data:{code:H.code}});200===a.statusCode&&(we.value=(null==(t=null==(e=a.data.result)?void 0:e.data)?void 0:t.data)||[]),ve.value=!1}();else{if(ue)return se.value={...ue},void(ue=null);Ce()}else{if(re)return oe.value={...re},void(re=null);Se()}})),l(X,(e=>{if(0!==e||Fe)if(1!==e||De){if(2===e&&!Le){if(de)return ne.value={...de},void(de=null);!async function(){const e=await Ae(Z);if(!e.length)return be.value=!0,void(_e.value=!1);_e.value=!1,de=ne.value=await je(e,90)}()}}else{if(ce)return ie.value={...ce},void(ce=null);!async function(){const e=await Ae(G);if(!e.length)return he.value=!0,void(ye.value=!1);ye.value=!1,ce=ie.value=await je(e)}()}else{if(ue)return se.value={...ue},void(ue=null);Ce()}})),l(oe,(async e=>{0===U.value&&(await w(),ke=await ee.value.init(E),ke.setOption(e))})),l(se,(async e=>{0===X.value&&(await w(),Fe=await te.value.init(E),Fe.setOption(e))})),l(ie,(async e=>{1===X.value&&(await w(),De=await ae.value.init(E),De.setOption(e))})),l(ne,(async e=>{2===X.value&&(await w(),Le=await le.value.init(E),Le.setOption(e))})),(e,t)=>{const a=L(o("uni-nav-bar"),D),l=f,d=L(o("up-col"),S),w=L(o("up-row"),$),F=L(o("up-skeleton"),A),N=L(o("up-tabs"),C),z=L(o("l-echart"),j),O=L(o("up-empty"),T),R=L(o("up-subsection"),M),P=L(o("up-button"),I);return s(),i(c,null,[n(a,{title:`${H.name} (${H.code})`,"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:r(p)},null,8,["title","onClickLeft"]),n(l,null,{default:u((()=>[n(F,{class:"p-20 border-bottom",rows:"2",title:!1,isArticle:!1,loading:fe.value},{default:u((()=>[n(w,null,{default:u((()=>[n(d,{span:"4"},{default:u((()=>[n(l,{style:{"font-size":"22px",color:"#ff4b2b"}},{default:u((()=>[m(y(J.value.current_price),1)])),_:1}),n(l,{class:"font-16-red align-center"},{default:u((()=>[n(l,{class:_({"down-color":parseFloat(J.value.current_price).toFixed(2)-parseFloat(J.value.yesterday_price)<0})},{default:u((()=>[m(y((parseFloat(J.value.current_price).toFixed(2)-parseFloat(J.value.yesterday_price)).toFixed(2)),1)])),_:1},8,["class"]),n(l,{class:_(["ml-10",{"down-color":100*(parseFloat(J.value.current_price).toFixed(2)-parseFloat(J.value.yesterday_price)).toFixed(2)/parseFloat(J.value.yesterday_price)<0}])},{default:u((()=>[m(y((100*(parseFloat(J.value.current_price).toFixed(2)-parseFloat(J.value.yesterday_price)).toFixed(2)/parseFloat(J.value.yesterday_price)).toFixed(2)+"%"),1)])),_:1},8,["class"])])),_:1})])),_:1}),n(d,{span:"4"},{default:u((()=>[n(l,{class:"align-center"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.openToday")),1)])),_:1}),n(l,{class:"font-12-red ml-10"},{default:u((()=>[m(y(J.value.open_price),1)])),_:1})])),_:1}),n(l,{class:"align-center mt-10"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.yesterdayHarvest")),1)])),_:1}),n(l,{class:"font-12 ml-10"},{default:u((()=>[m(y(J.value.yesterday_price),1)])),_:1})])),_:1})])),_:1}),n(d,{span:"4"},{default:u((()=>[n(l,{class:"align-center"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.highest")),1)])),_:1}),n(l,{class:"font-12 ml-10"},{default:u((()=>[m(y(J.value.highest),1)])),_:1})])),_:1}),n(l,{class:"align-center mt-10"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.lowest")),1)])),_:1}),n(l,{class:"font-12 ml-10"},{default:u((()=>[m(y(J.value.lowest),1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1},8,["loading"]),n(F,{class:"p-20 border-bottom",rows:"2",title:!1,isArticle:!1,loading:fe.value},{default:u((()=>[n(w,null,{default:u((()=>[n(d,{span:"4"},{default:u((()=>[n(l,{class:"align-center"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.volume")),1)])),_:1}),n(l,{class:"font-12-red ml-10"},{default:u((()=>[m(y(J.value.volume),1)])),_:1})])),_:1}),n(l,{class:"align-center mt-10"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.transactionAmount")),1)])),_:1}),n(l,{class:"font-12 ml-10"},{default:u((()=>[m(y(J.value.turnover)+y(e.$t("common.text.tenThousand")),1)])),_:1})])),_:1})])),_:1}),n(d,{span:"4"},{default:u((()=>[n(l,{class:"align-center"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.priceToEarningsRatio")),1)])),_:1}),n(l,{class:"font-12-red ml-10"},{default:u((()=>[m(y(J.value.pe_ratio),1)])),_:1})])),_:1}),n(l,{class:"align-center mt-10"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.priceToBookRatio")),1)])),_:1}),n(l,{class:"font-12 ml-10"},{default:u((()=>[m(y(J.value.pb_ratio),1)])),_:1})])),_:1})])),_:1}),n(d,{span:"4"},{default:u((()=>[n(l,{class:"align-center"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.turnoverRate")),1)])),_:1}),n(l,{class:"font-12 ml-10"},{default:u((()=>[m(y(J.value.buy_chang_price),1)])),_:1})])),_:1}),n(l,{class:"align-center mt-10"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.theTotalMarketCapitalization")),1)])),_:1}),n(l,{class:"font-12 ml-10"},{default:u((()=>[m(y(J.value.total_market_value)+y(e.$t("common.text.100Million")),1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1},8,["loading"]),n(l,{class:"line"}),n(N,{class:"pl-20",list:Q,currentq:U.value,onChange:t[0]||(t[0]=e=>U.value=e.index)},null,8,["currentq"]),v(n(F,{class:"plr-30 mt-20",style:{height:"780rpx"},rows:"6","rows-height":50,title:!1,isArticle:!1,loading:pe.value},{default:u((()=>[ge.value?h("",!0):(s(),x(z,{key:0,ref_key:"minuteChartRef",ref:ee},null,512)),ge.value?(s(),x(l,{key:1,class:"no-data"},{default:u((()=>[n(O,{mode:"data",icon:"/static/empty/empty-data.png"})])),_:1})):h("",!0)])),_:1},8,["loading"]),[[g,0===U.value]]),1===U.value?(s(),x(l,{key:0,class:"plr-30 mt-20"},{default:u((()=>[n(R,{mode:"subsection","active-color":"rgb(255, 69, 0)",list:Y,current:X.value,"key-name":"name",onChange:t[1]||(t[1]=e=>X.value=e)},null,8,["current"])])),_:1})):h("",!0),v(n(l,{class:"plr-30"},{default:u((()=>[v(n(F,{class:"mt-20",style:{height:"780rpx"},rows:"7","rows-height":50,"rows-width":"690rpx",title:!1,loading:me.value},{default:u((()=>[xe.value?h("",!0):(s(),x(z,{key:0,ref_key:"dayKChartRef",ref:te},null,512)),xe.value?(s(),x(l,{key:1,class:"no-data"},{default:u((()=>[n(O,{mode:"data",icon:"/static/empty/empty-data.png"})])),_:1})):h("",!0)])),_:1},8,["loading"]),[[g,0===X.value]]),v(n(F,{class:"mt-20",style:{height:"780rpx"},rows:"7","rows-height":50,"rows-width":"690rpx",title:!1,loading:ye.value},{default:u((()=>[he.value?h("",!0):(s(),x(z,{key:0,ref_key:"weekKChartRef",ref:ae},null,512)),he.value?(s(),x(l,{key:1,class:"no-data"},{default:u((()=>[n(O,{mode:"data",icon:"/static/empty/empty-data.png"})])),_:1})):h("",!0)])),_:1},8,["loading"]),[[g,1===X.value]]),v(n(F,{class:"mt-20",style:{height:"780rpx"},rows:"7","rows-height":50,"rows-width":"690rpx",title:!1,loading:_e.value},{default:u((()=>[be.value?h("",!0):(s(),x(z,{key:0,ref_key:"monthKChartRef",ref:le},null,512)),be.value?(s(),x(l,{key:1,class:"no-data"},{default:u((()=>[n(O,{mode:"data",icon:"/static/empty/empty-data.png"})])),_:1})):h("",!0)])),_:1},8,["loading"]),[[g,2===X.value]])])),_:1},512),[[g,1===U.value]]),2===U.value?(s(),x(F,{key:1,class:_({"p-30":ve.value}),rows:"8","rows-height":"60",title:!1,loading:ve.value,isArticle:!1},{default:u((()=>[n(l,{class:"information-list"},{default:u((()=>[(s(!0),i(c,null,b(we.value,(e=>(s(),x(l,{class:"information-item plr-30 ptb-20 border-bottom",key:e.ID,onClick:t=>r(k)({id:e.ID,code:`st${H.code}`})},{default:u((()=>[n(l,{class:"information-item__title font-14 ellipsis-1"},{default:u((()=>[m(y(e.title),1)])),_:2},1024),n(l,{class:"information-item__content font-14-grey mt-10"},{default:u((()=>[n(l,null,{default:u((()=>[m(y(e.type),1)])),_:2},1024),n(l,null,{default:u((()=>[m(y(e.origin),1)])),_:2},1024),n(l,null,{default:u((()=>[m(y(e.date.slice(0,-8)),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1})])),_:1},8,["class","loading"])):h("",!0),n(l,{class:"line"}),0===U.value||1===U.value?(s(),x(F,{key:2,class:"p-30",rows:"4",title:!1,loading:fe.value,isArticle:!1},{default:u((()=>[n(w,{gutter:"20"},{default:u((()=>[n(d,{span:"6"},{default:u((()=>[n(l,{class:"pl-20 pb-20 center-center border-bottom font-12"},{default:u((()=>[m(y(e.$t("stockDetail.text.buyingPosition")),1)])),_:1}),n(l,null,{default:u((()=>[n(l,{class:"between-center h-30"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.buyOne")),1)])),_:1}),n(l,{class:"font-12-red"},{default:u((()=>[m(y(parseFloat(J.value.buy_one_price).toFixed(2)),1)])),_:1}),n(l,{class:"font-12-grey"},{default:u((()=>[m(y(parseFloat(J.value.buy_one_amount).toFixed(2)),1)])),_:1})])),_:1}),n(l,{class:"between-center h-30"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.buyTwo")),1)])),_:1}),n(l,{class:"font-12-red"},{default:u((()=>[m(y(parseFloat(J.value.buy_two_price).toFixed(2)),1)])),_:1}),n(l,{class:"font-12-grey"},{default:u((()=>[m(y(parseFloat(J.value.buy_two_amount).toFixed(2)),1)])),_:1})])),_:1}),n(l,{class:"between-center h-30"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.buyThree")),1)])),_:1}),n(l,{class:"font-12-red"},{default:u((()=>[m(y(parseFloat(J.value.buy_three_price).toFixed(2)),1)])),_:1}),n(l,{class:"font-12-grey"},{default:u((()=>[m(y(parseFloat(J.value.buy_three_amount).toFixed(2)),1)])),_:1})])),_:1})])),_:1})])),_:1}),n(d,{span:"6"},{default:u((()=>[n(l,{class:"pl-20 pb-20 center-center border-bottom font-12"},{default:u((()=>[m(y(e.$t("stockDetail.text.sellingFile")),1)])),_:1}),n(l,null,{default:u((()=>[n(l,{class:"between-center h-30"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.sellOne")),1)])),_:1}),n(l,{class:"font-12-red"},{default:u((()=>[m(y(parseFloat(J.value.sell_one_price).toFixed(2)),1)])),_:1}),n(l,{class:"font-12-grey"},{default:u((()=>[m(y(parseFloat(J.value.sell_one_amount).toFixed(2)),1)])),_:1})])),_:1}),n(l,{class:"between-center h-30"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.sellTwo")),1)])),_:1}),n(l,{class:"font-12-red"},{default:u((()=>[m(y(parseFloat(J.value.sell_two_price).toFixed(2)),1)])),_:1}),n(l,{class:"font-12-grey"},{default:u((()=>[m(y(parseFloat(J.value.sell_two_amount).toFixed(2)),1)])),_:1})])),_:1}),n(l,{class:"between-center h-30"},{default:u((()=>[n(l,{class:"font-12-grey"},{default:u((()=>[m(y(e.$t("stockDetail.text.sellThree")),1)])),_:1}),n(l,{class:"font-12-red"},{default:u((()=>[m(y(parseFloat(J.value.sell_three_price).toFixed(2)),1)])),_:1}),n(l,{class:"font-12-grey"},{default:u((()=>[m(y(parseFloat(J.value.sell_three_amount).toFixed(2)),1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1},8,["loading"])):h("",!0),2!==U.value?(s(),x(l,{key:3,class:"line"})):h("",!0),n(l,{class:"p-30 operation-btn"},{default:u((()=>[V.value?(s(),x(P,{key:1,color:"rgb(255, 69, 0)",text:e.$t("stockDetail.text.cancelSelfSelection"),onClick:Me},null,8,["text"])):(s(),x(P,{key:0,color:"rgb(255, 69, 0)",text:e.$t("stockDetail.text.addToFavorite"),onClick:Te},null,8,["text"]))])),_:1})])),_:1})],64)}}},[["__scopeId","data-v-f7c323db"]]);export{B as default};
