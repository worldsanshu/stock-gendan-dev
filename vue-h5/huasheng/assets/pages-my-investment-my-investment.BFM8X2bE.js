import{V as t,ap as e,ai as a,x as s,y as l,D as n,H as o,Y as i,A as d,F as m,C as r,Z as u,I as c,J as _,cp as f,z as y,G as v,E as p,B as x,aq as b}from"./index-BpfZrajd.js";import{_ as g}from"./uni-nav-bar.BZQCfLjz.js";import{o as h,r as I}from"./uni-app.es.C3RDKkKF.js";import{K as $}from"./index.B50efdrW.js";import{_ as k}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CkblPvCD.js";import"./uni-status-bar.CQB75Tz1.js";const F=k(Object.assign({name:"trade"},{__name:"my-investment",setup(k){const F=t(1),j=t(1),C=t([]),N=t();function E(){$({page:F.value,type:j.value}).then((t=>{C.value=t.data.list.data,N.value=t.data.statistics}))}return e((()=>{E()})),h((async()=>{E(),await Promise.resolve(),a()})),(t,e)=>{const a=I(s("uni-nav-bar"),g),h=r;return l(),n(m,null,[o(a,{title:t.$t("page.title.myInvestment"),"status-bar":"","background-color":"#FF3C36",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:i(u)},null,8,["title","onClickLeft"]),o(h,{class:"root"},{default:d((()=>[o(h,{class:"red-block"},{default:d((()=>[o(h,{class:"font-12-inverse"},{default:d((()=>[c(_(t.$t("myInvestment.text.lumpSum"))+"("+_(t.$t("common.text.currencyUnit"))+")",1)])),_:1}),o(h,{class:"font-30-inverse mt-15"},{default:d((()=>[c(_(N.value.total_hold_money?i(f)(Number(N.value.total_hold_money).toFixed(2)):"0.00"),1)])),_:1}),o(h,{class:"data-row mt-50"},{default:d((()=>[o(h,{class:"data-item"},{default:d((()=>[o(h,{class:"font-12"},{default:d((()=>[c(_(t.$t("myInvestment.text.yesterdayEarnings"))+"("+_(t.$t("common.text.currencyUnit"))+")",1)])),_:1}),o(h,{class:"font-18-red mt-5"},{default:d((()=>[c(_(N.value.total_yesterday_income?Number(N.value.total_yesterday_income).toFixed(2):"0.00"),1)])),_:1})])),_:1}),o(h,{class:"data-item"},{default:d((()=>[o(h,{class:"font-12"},{default:d((()=>[c(_(t.$t("myInvestment.text.cumulativeIncome")),1)])),_:1}),o(h,{class:"font-18-red mt-5"},{default:d((()=>[c(_(N.value.total_hold_income?Number(N.value.total_hold_income).toFixed(2):"0.00"),1)])),_:1})])),_:1})])),_:1})])),_:1}),C.value.length?(l(),y(h,{key:0,class:"list-box"},{default:d((()=>[o(h,{class:"list-header"},{default:d((()=>[c(_(t.$t("myInvestment.text.myOrder")),1)])),_:1})])),_:1})):v("",!0),o(h,{class:"list"},{default:d((()=>[(l(!0),n(m,null,p(C.value,(e=>(l(),y(h,{class:"list-item",key:e,onClick:t=>{return a=e.id,void b("/pages/asset-details/asset-details",{id:a});var a}},{default:d((()=>[o(h,{class:"list-item-header"},{default:d((()=>[3===e.order_type?(l(),y(h,{key:0,style:{color:"#ff7d00"}},{default:d((()=>[c(_(t.$t("common.text.oneClickSmartInvestment")),1)])),_:1})):(l(),y(h,{key:1},{default:d((()=>[c(_(t.$t("common.text.dailySmartInvestment")),1)])),_:1}))])),_:2},1024),o(h,{style:{display:"flex"}},{default:d((()=>[o(h,{class:"list-item-col font-14"},{default:d((()=>[o(h,null,{default:d((()=>[c(_(e.yesterday_income?e.yesterday_income.toFixed(2):0),1)])),_:2},1024),o(h,null,{default:d((()=>[c(_(t.$t("myInvestment.text.financeYesterdayEarnings")),1)])),_:1})])),_:2},1024),o(h,{class:"list-item-col"},{default:d((()=>[o(h,null,{default:d((()=>[o(h,{class:x([e.hold_income&&e.hold_income<0?"on":""])},{default:d((()=>[c(_(e.hold_income=Number(e.hold_income).toFixed(2)),1)])),_:2},1032,["class"]),o(h,{class:x([e.earning_rate&&e.earning_rate<0?"on":""])},{default:d((()=>[c(_(e.earning_rate=Number(e.earning_rate).toFixed(2))+"%",1)])),_:2},1032,["class"])])),_:2},1024),o(h,null,{default:d((()=>[c(_(t.$t("myInvestment.text.holdingIncome")),1)])),_:1})])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1})])),_:1})],64)}}}),[["__scopeId","data-v-1585cf4d"]]);export{F as default};
