import{T as a,V as t,aj as e,ae as l,x as s,y as o,z as n,B as u,C as r,H as d,I as i,J as c,Y as m,ar as _,G as f,D as v,E as y,F as p,q as x,A as g,r as k}from"./index-DugkB_-4.js";import{_ as $}from"./u-empty.CQpJXShg.js";import{c as b,d as j,r as h}from"./uni-app.es.CX-CC-lz.js";import{_ as w}from"./u-loadmore.DoeN8jY1.js";import{e as I}from"./index.CCikdCXX.js";import{_ as A}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.Ca1c56qc.js";import"./u-line.BpwwmqXn.js";import"./u-loading-icon.CCS9DSWM.js";const C=A(Object.assign({name:"trade"},{__name:"trade",setup(A){const C=a(),U=t([]),F=t(0),B=t(0),D=t(0),E=t(1),N=t(!1),O=t("loadmore"),S=t(0);async function V(){O.value="loading";const{error:a,message:t,data:e}=await I({type:1,page:E.value});if(!a)return{data:e,error:a};await x({title:t,icon:"none"})}async function q(){E.value=1;const{error:a,data:t}=await V();a?O.value="error":(U.value=t.list.data,N.value=!t.list.data.length,O.value=U.value.length<10?"nomore":"loadmore",F.value=t.statistics.total_hold_money,B.value=t.statistics.total_hold_income,D.value=t.statistics.total_yesterday_income,S.value=t.statistics.total_amount_available)}async function z(){if("nomore"===O.value)return;E.value++;const{data:a,error:t}=await V();t?O.value="error":a.length?(O.value="loadmore",U.value=U.value.concat(a)):O.value="nomore"}return b((async()=>{await q(),e()})),j(z),q(),l((()=>C.token),(a=>{a&&q()})),(a,t)=>{const e=u,l=h(s("up-empty"),$),x=h(s("up-loadmore"),w);return r(),o(e,{class:"root"},{default:n((()=>[d(e,{class:"red-block"},{default:n((()=>[d(e,{class:"font-12-inverse"},{default:n((()=>[i(c(a.$t("trade.text.amountAvailable"))+"("+c(a.$t("common.text.currencyUnit"))+")",1)])),_:1}),d(e,{class:"font-30-inverse"},{default:n((()=>[i(c(S.value<0?0:S.value.toFixed(2)),1)])),_:1}),d(e,{class:"data-row"},{default:n((()=>[d(e,{class:"data-item"},{default:n((()=>[d(e,null,{default:n((()=>[i(c(a.$t("trade.text.totalInvestmentAmount"))+"("+c(a.$t("common.text.currencyUnit"))+")",1)])),_:1}),d(e,null,{default:n((()=>[i(c(F.value<0?0:Number(F.value).toFixed(2)),1)])),_:1})])),_:1}),d(e,{class:"data-item"},{default:n((()=>[d(e,null,{default:n((()=>[i(c(a.$t("trade.text.cumulativeIncome")),1)])),_:1}),m(_)(B.value)>0?(r(),o(e,{key:0},{default:n((()=>[i(c(m(_)(B.value)),1)])),_:1})):f("",!0),m(_)(B.value)<0?(r(),o(e,{key:1,style:{color:"rgb(6, 159, 23)"}},{default:n((()=>[i(c(m(_)(B.value)),1)])),_:1})):f("",!0)])),_:1})])),_:1})])),_:1}),U.value.length?(r(),o(e,{key:0,class:"list-box"},{default:n((()=>[d(e,{class:"list-header"},{default:n((()=>[i(c(a.$t("trade.text.myOrder")),1)])),_:1})])),_:1})):f("",!0),d(e,{class:"list"},{default:n((()=>[(r(!0),v(p,null,y(U.value,(t=>(r(),o(e,{class:"list-item",key:t,onClick:a=>async function(a){console.log("触发了事件！"),k({url:`/pages/asset-details/asset-details?id=${a}`})}(t.id)},{default:n((()=>[d(e,{class:"list-item-header"},{default:n((()=>[3===t.order_type?(r(),o(e,{key:0,style:{color:"#ff7d00"}},{default:n((()=>[i(c(a.$t("common.text.oneClickSmartInvestment")),1)])),_:1})):(r(),o(e,{key:1},{default:n((()=>[i(c(a.$t("common.text.dailySmartInvestment")),1)])),_:1})),d(e,{class:"mnis"},{default:n((()=>[i(c(a.$t("trade.text.orderNumber"))+c(t.order_sn),1)])),_:2},1024)])),_:2},1024),d(e,{style:{display:"flex"}},{default:n((()=>[d(e,{class:"list-item-col font-14"},{default:n((()=>[d(e,null,{default:n((()=>[i(c(t.money?t.money:0),1)])),_:2},1024),d(e,null,{default:n((()=>[i(c(a.$t("trade.text.followUpInvestmentAmount")),1)])),_:1})])),_:2},1024),d(e,{class:"list-item-col"},{default:n((()=>[d(e,null,{default:n((()=>[d(e,{class:g([t.balance&&t.balance<0?"on":""])},{default:n((()=>[i(c(m(_)(t.balance)),1)])),_:2},1032,["class"]),d(e,{class:g([t.earning_rate&&t.earning_rate<0?"on":""])},{default:n((()=>[i(c(t.earning_rate)+"%",1)])),_:2},1032,["class"])])),_:2},1024),d(e,null,{default:n((()=>[i(c(a.$t("trade.text.holdingIncome")),1)])),_:1})])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1}),N.value?(r(),o(l,{key:1,mode:"data",icon:"/static/empty/empty-data.png"})):f("",!0),N.value?f("",!0):(r(),o(x,{key:2,status:O.value,onClick:z},null,8,["status"]))])),_:1})}}}),[["__scopeId","data-v-5a40f631"]]);export{C as default};
