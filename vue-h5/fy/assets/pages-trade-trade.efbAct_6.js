import{T as a,V as t,ah as e,ad as l,x as s,y as o,z as n,A as u,C as r,H as d,I as i,J as c,Y as m,aq as _,G as f,D as v,E as y,F as p,q as x,B as g,r as k}from"./index-v5N4FCa5.js";import{_ as b}from"./u-empty.C3tH2qBO.js";import{o as $,a as h,r as j}from"./uni-app.es.BBb7LdIV.js";import{_ as I}from"./u-loadmore.CXNUfWE1.js";import{e as w}from"./index.Dm55e1Sd.js";import{_ as C}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.C6i9Eu8q.js";import"./u-line.DYw27FgS.js";import"./u-loading-icon.Cck0q0wJ.js";const A=C(Object.assign({name:"trade"},{__name:"trade",setup(C){const A=a(),F=t([]),N=t(0),O=t(0),S=t(0),U=t(1),q=t(!1),T=t("loadmore"),V=t(0);async function z(){T.value="loading";const{error:a,message:t,data:e}=await w({type:1,page:U.value});if(!a)return{data:e,error:a};await x({title:t,icon:"none"})}async function B(){U.value=1;const{error:a,data:t}=await z();a?T.value="error":(F.value=t.list.data,q.value=!t.list.data.length,T.value=F.value.length<10?"nomore":"loadmore",N.value=t.statistics.total_hold_money,O.value=t.statistics.total_hold_income,S.value=t.statistics.total_yesterday_income,V.value=t.statistics.total_amount_available)}async function D(){if("nomore"===T.value)return;U.value++;const{data:a,error:t}=await z();t?T.value="error":a.length?(T.value="loadmore",F.value=F.value.concat(a)):T.value="nomore"}return $((async()=>{await B(),e()})),h(D),B(),l((()=>A.token),(a=>{a&&B()})),(a,t)=>{const e=r,l=j(s("up-empty"),b),x=j(s("up-loadmore"),I);return o(),n(e,{class:"root"},{default:u((()=>[d(e,{class:"red-block"},{default:u((()=>[d(e,{class:"font-12-inverse"},{default:u((()=>[i(c(a.$t("trade.text.amountAvailable"))+"("+c(a.$t("common.text.currencyUnit"))+")",1)])),_:1}),d(e,{class:"font-30-inverse"},{default:u((()=>[i(c(V.value.toFixed(2)),1)])),_:1}),d(e,{class:"data-row"},{default:u((()=>[d(e,{class:"data-item"},{default:u((()=>[d(e,null,{default:u((()=>[i(c(a.$t("trade.text.totalInvestmentAmount"))+"("+c(a.$t("common.text.currencyUnit"))+")",1)])),_:1}),d(e,null,{default:u((()=>[i(c(Number(N.value).toFixed(2)),1)])),_:1})])),_:1}),d(e,{class:"data-item"},{default:u((()=>[d(e,null,{default:u((()=>[i(c(a.$t("trade.text.cumulativeIncome")),1)])),_:1}),m(_)(O.value)>0?(o(),n(e,{key:0},{default:u((()=>[i(c(m(_)(O.value)),1)])),_:1})):f("",!0),m(_)(O.value)<0?(o(),n(e,{key:1,style:{color:"rgb(6, 159, 23)"}},{default:u((()=>[i(c(m(_)(O.value)),1)])),_:1})):f("",!0)])),_:1})])),_:1})])),_:1}),F.value.length?(o(),n(e,{key:0,class:"list-box"},{default:u((()=>[d(e,{class:"list-header"},{default:u((()=>[i(c(a.$t("trade.text.myOrder")),1)])),_:1})])),_:1})):f("",!0),d(e,{class:"list"},{default:u((()=>[(o(!0),v(p,null,y(F.value,(t=>(o(),n(e,{class:"list-item",key:t,onClick:a=>async function(a){console.log("触发了事件！"),k({url:`/pages/asset-details/asset-details?id=${a}`})}(t.id)},{default:u((()=>[d(e,{class:"list-item-header"},{default:u((()=>[3===t.order_type?(o(),n(e,{key:0,style:{color:"#ff7d00"}},{default:u((()=>[i(c(a.$t("common.text.oneClickSmartInvestment")),1)])),_:1})):(o(),n(e,{key:1},{default:u((()=>[i(c(a.$t("common.text.dailySmartInvestment")),1)])),_:1})),d(e,{class:"mnis"},{default:u((()=>[i(c(a.$t("trade.text.orderNumber"))+c(t.order_sn),1)])),_:2},1024)])),_:2},1024),d(e,{style:{display:"flex"}},{default:u((()=>[d(e,{class:"list-item-col font-14"},{default:u((()=>[d(e,null,{default:u((()=>[i(c(t.money?t.money:0),1)])),_:2},1024),d(e,null,{default:u((()=>[i(c(a.$t("trade.text.followUpInvestmentAmount")),1)])),_:1})])),_:2},1024),d(e,{class:"list-item-col"},{default:u((()=>[d(e,null,{default:u((()=>[d(e,{class:g([t.balance&&t.balance<0?"on":""])},{default:u((()=>[i(c(m(_)(t.balance)),1)])),_:2},1032,["class"]),d(e,{class:g([t.earning_rate&&t.earning_rate<0?"on":""])},{default:u((()=>[i(c(t.earning_rate)+"%",1)])),_:2},1032,["class"])])),_:2},1024),d(e,null,{default:u((()=>[i(c(a.$t("trade.text.holdingIncome")),1)])),_:1})])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1}),q.value?(o(),n(l,{key:1,mode:"data",icon:"/static/empty/empty-data.png"})):f("",!0),q.value?f("",!0):(o(),n(x,{key:2,status:T.value,onClick:D},null,8,["status"]))])),_:1})}}}),[["__scopeId","data-v-09606a2b"]]);export{A as default};
