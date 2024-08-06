import{T as a,V as t,aj as e,ae as l,x as s,y as o,z as n,A as u,C as r,H as d,I as i,J as c,Y as m,ar as _,G as f,D as v,E as y,F as p,q as x,B as g,r as k}from"./index-DxUBkcQO.js";import{_ as b}from"./u-empty.B-uqFSHu.js";import{c as $,d as j,r as h}from"./uni-app.es.WyEFFhdr.js";import{_ as w}from"./u-loadmore.BbrrZ_01.js";import{e as I}from"./index.B2Jj0BgA.js";import{_ as A}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.BCEdui8G.js";import"./u-line.BXTok7nE.js";import"./u-loading-icon.DIzsuW0j.js";const C=A(Object.assign({name:"trade"},{__name:"trade",setup(A){const C=a(),U=t([]),F=t(0),B=t(0),D=t(0),E=t(1),N=t(!1),O=t("loadmore"),S=t(0);async function V(){O.value="loading";const{error:a,message:t,data:e}=await I({type:1,page:E.value});if(!a)return{data:e,error:a};await x({title:t,icon:"none"})}async function q(){E.value=1;const{error:a,data:t}=await V();a?O.value="error":(U.value=t.list.data,N.value=!t.list.data.length,O.value=U.value.length<10?"nomore":"loadmore",F.value=t.statistics.total_hold_money,B.value=t.statistics.total_hold_income,D.value=t.statistics.total_yesterday_income,S.value=t.statistics.total_amount_available)}async function z(){if("nomore"===O.value)return;E.value++;const{data:a,error:t}=await V();t?O.value="error":a.length?(O.value="loadmore",U.value=U.value.concat(a)):O.value="nomore"}return $((async()=>{await q(),e()})),j(z),q(),l((()=>C.token),(a=>{a&&q()})),(a,t)=>{const e=r,l=h(s("up-empty"),b),x=h(s("up-loadmore"),w);return o(),n(e,{class:"root"},{default:u((()=>[d(e,{class:"red-block"},{default:u((()=>[d(e,{class:"font-12-inverse"},{default:u((()=>[i(c(a.$t("trade.text.amountAvailable"))+"("+c(a.$t("common.text.currencyUnit"))+")",1)])),_:1}),d(e,{class:"font-30-inverse"},{default:u((()=>[i(c(S.value.toFixed(2)),1)])),_:1}),d(e,{class:"data-row"},{default:u((()=>[d(e,{class:"data-item"},{default:u((()=>[d(e,null,{default:u((()=>[i(c(a.$t("trade.text.totalInvestmentAmount"))+"("+c(a.$t("common.text.currencyUnit"))+")",1)])),_:1}),d(e,null,{default:u((()=>[i(c(Number(F.value).toFixed(2)),1)])),_:1})])),_:1}),d(e,{class:"data-item"},{default:u((()=>[d(e,null,{default:u((()=>[i(c(a.$t("trade.text.cumulativeIncome")),1)])),_:1}),m(_)(B.value)>0?(o(),n(e,{key:0},{default:u((()=>[i(c(m(_)(B.value)),1)])),_:1})):f("",!0),m(_)(B.value)<0?(o(),n(e,{key:1,style:{color:"rgb(6, 159, 23)"}},{default:u((()=>[i(c(m(_)(B.value)),1)])),_:1})):f("",!0)])),_:1})])),_:1})])),_:1}),U.value.length?(o(),n(e,{key:0,class:"list-box"},{default:u((()=>[d(e,{class:"list-header"},{default:u((()=>[i(c(a.$t("trade.text.myOrder")),1)])),_:1})])),_:1})):f("",!0),d(e,{class:"list"},{default:u((()=>[(o(!0),v(p,null,y(U.value,(t=>(o(),n(e,{class:"list-item",key:t,onClick:a=>async function(a){console.log("触发了事件！"),k({url:`/pages/asset-details/asset-details?id=${a}`})}(t.id)},{default:u((()=>[d(e,{class:"list-item-header"},{default:u((()=>[3===t.order_type?(o(),n(e,{key:0,style:{color:"#ff7d00"}},{default:u((()=>[i(c(a.$t("common.text.oneClickSmartInvestment")),1)])),_:1})):(o(),n(e,{key:1},{default:u((()=>[i(c(a.$t("common.text.dailySmartInvestment")),1)])),_:1})),d(e,{class:"mnis"},{default:u((()=>[i(c(a.$t("trade.text.orderNumber"))+c(t.order_sn),1)])),_:2},1024)])),_:2},1024),d(e,{style:{display:"flex"}},{default:u((()=>[d(e,{class:"list-item-col font-14"},{default:u((()=>[d(e,null,{default:u((()=>[i(c(t.money?t.money:0),1)])),_:2},1024),d(e,null,{default:u((()=>[i(c(a.$t("trade.text.followUpInvestmentAmount")),1)])),_:1})])),_:2},1024),d(e,{class:"list-item-col"},{default:u((()=>[d(e,null,{default:u((()=>[d(e,{class:g([t.balance&&t.balance<0?"on":""])},{default:u((()=>[i(c(m(_)(t.balance)),1)])),_:2},1032,["class"]),d(e,{class:g([t.earning_rate&&t.earning_rate<0?"on":""])},{default:u((()=>[i(c(t.earning_rate)+"%",1)])),_:2},1032,["class"])])),_:2},1024),d(e,null,{default:u((()=>[i(c(a.$t("trade.text.holdingIncome")),1)])),_:1})])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1}),N.value?(o(),n(l,{key:1,mode:"data",icon:"/static/empty/empty-data.png"})):f("",!0),N.value?f("",!0):(o(),n(x,{key:2,status:O.value,onClick:z},null,8,["status"]))])),_:1})}}}),[["__scopeId","data-v-09606a2b"]]);export{C as default};
