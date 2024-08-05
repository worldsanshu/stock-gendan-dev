import{R as e,V as t,x as a,y as l,D as i,H as s,Y as r,A as n,F as o,q as c,C as u,at as d,bW as p,I as m,bX as f,bY as g,z as h,J as _,bZ as y,G as k,b_ as b,b$ as v,E as C,r as x,O as j,c0 as w}from"./index-BSZMSohE.js";import{_ as z}from"./uni-nav-bar.DaV7kknO.js";import{d as P,r as I}from"./uni-app.es.xTxZrAO-.js";import{_ as O}from"./uni-icons.UvJfiB1T.js";import{_ as R}from"./uni-card.W8qPSQXN.js";import{_ as T}from"./uni-section.DL9TJJdr.js";import{_ as A,a as D}from"./uni-list.BAmADFzC.js";import{_ as J}from"./u-skeleton.v08ghf2y.js";import{a as L}from"./index.D3LolpWH.js";import{c as Y}from"./wallet.BMFo6IIZ.js";import{_ as $}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.DM8Vigxm.js";import"./uni-badge.D3JSGdBF.js";const q=$(Object.assign({name:"user-center-recharge"},{__name:"user-center-recharge",setup($){const{t:q}=e();let E=t("transparent");const F=t([]),G=t([]),H=t({}),N=t(""),S=t(""),U=t(""),V=t(""),W=t(!0);async function X(){x({url:"/pages/customer-service/customer-service"})}return L().then((e=>{H.value=e.data.module})),P((e=>{e.scrollTop>20?E.value="#ff7a4d":E.value="transparent"})),async function(){const{error:e,message:t,data:a}=await Y();if(e)await c({title:t,icon:"none"});else{for(const e of a.list)switch(e.type){case"currency":N.value="currency";break;case"bank":S.value="bank";break;case"wechatpay":U.value="wechatpay";break;case"alipay":V.value="alipay"}F.value=a.list.map((e=>e.type)),G.value=a.list.filter((e=>1===e.is_online)),W.value=!1}}(),(e,t)=>{const c=I(a("uni-nav-bar"),z),x=u,P=I(a("uni-icons"),O),L=j,Y=I(a("uni-card"),R),$=I(a("uni-section"),T),F=I(a("uni-list-item"),A),Z=I(a("uni-list"),D),B=I(a("up-skeleton"),J);return l(),i(o,null,[s(c,{title:"资金管理","status-bar":"","background-color":r(E),color:"#fff","right-icon":"more-filled",fixed:"",border:!1,"left-icon":"left",onClickLeft:r(d),onClickRight:r(p)},null,8,["background-color","onClickLeft","onClickRight"]),s(x,{class:"page-bg"}),s(x,{class:"root"},{default:n((()=>[s(x,{class:"page-header"},{default:n((()=>[s(x,{class:"header-option"},{default:n((()=>[s(x,{class:"header-option-item header-option-item-active"},{default:n((()=>[m("充值")])),_:1}),s(x,{class:"header-option-item",onClick:r(f)},{default:n((()=>[m("提现")])),_:1},8,["onClick"]),s(x,{class:"header-option-item",onClick:r(g)},{default:n((()=>[m("钱包")])),_:1},8,["onClick"])])),_:1})])),_:1}),s(x,{class:"body"},{default:n((()=>[s(B,{rows:"3","rows-height":"150",title:!1,isArticle:!1,loading:W.value},{default:n((()=>[N.value?(l(),h(x,{key:0,onClick:r(y)},{default:n((()=>[s($,{class:"mb-10 round-top",title:r(q)("recharge.text.virtualCurrency"),type:"line"},{right:n((()=>[s(P,{type:"right",size:"20"})])),default:n((()=>[s(Y,{class:"mt-20 round-bottom",title:r(q)("recharge.text.virtualCurrency"),"is-full":"","is-shadow":!1,thumbnail:"/static/images/cryptocurrency-payment.png"},{default:n((()=>[s(L,null,{default:n((()=>[m(_(r(q)("recharge.text.paymentDescription")),1)])),_:1})])),_:1},8,["title"])])),_:1},8,["title"])])),_:1},8,["onClick"])):k("",!0),S.value?(l(),h(x,{key:1,onClick:r(b)},{default:n((()=>[s($,{class:"mt-20 round-top",title:r(q)("recharge.text.bankCard"),type:"line"},{right:n((()=>[s(P,{type:"right",size:"20"})])),default:n((()=>[s(Y,{class:"mt-20 round-bottom",title:r(q)("recharge.text.bankCard"),"is-full":"","is-shadow":!1,thumbnail:"/static/images/union-pay.png"},{default:n((()=>[s(L,null,{default:n((()=>[m("信用卡、借记卡等银行卡充值方式")])),_:1})])),_:1},8,["title"])])),_:1},8,["title"])])),_:1},8,["onClick"])):k("",!0),U.value||V.value?(l(),h(x,{key:2},{default:n((()=>[s($,{class:"mt-20 round-top",title:r(q)("recharge.text.onlinePayment"),type:"line"},{default:n((()=>[s(Z,{border:""},{default:n((()=>[U.value?(l(),h(F,{key:0,thumb:"/static/images/weixin-pay.png",title:r(q)("recharge.text.wechatPay"),clickable:"",link:"",onClick:t[0]||(t[0]=e=>r(v)({type:U.value}))},null,8,["title"])):k("",!0),V.value?(l(),h(F,{key:1,thumb:"/static/images/zhifubao-pay.png",title:r(q)("recharge.text.aliPay"),clickable:"",link:"",onClick:t[1]||(t[1]=e=>r(v)({type:"alipay"}))},null,8,["title"])):k("",!0)])),_:1})])),_:1},8,["title"])])),_:1})):k("",!0),G.value.length?(l(),h(x,{key:3},{default:n((()=>[s($,{class:"mt-20 round-top",title:"三方钱包充值",type:"line"},{default:n((()=>[(l(!0),i(o,null,C(G.value,((e,t)=>(l(),h(Z,{key:t,border:""},{default:n((()=>[s(F,{thumb:e.logo_url,title:e.name,clickable:"",link:"",onClick:t=>r(w)({oItem:JSON.stringify(e)})},null,8,["thumb","title","onClick"])])),_:2},1024)))),128))])),_:1})])),_:1})):k("",!0),1==H.value.recharge_customer_service?(l(),h($,{key:4,class:"mt-20 round-top",title:"线下充值请联系客服",type:"line"},{default:n((()=>[s(Z,{border:""},{default:n((()=>[s(F,{thumb:"/static/images/card-8.png",title:"在线客服",clickable:"",link:"",onClick:X})])),_:1})])),_:1})):k("",!0)])),_:1},8,["loading"]),s(x,{class:"mt-50 font-16-red-bold"},{default:n((()=>[m(_(r(q)("recharge.text.tipTitle")),1)])),_:1}),s(x,{class:"mt-20 font-12-grey"},{default:n((()=>[m(_(r(q)("recharge.text.tip1")),1)])),_:1}),s(x,{class:"mt-20 font-12-grey"},{default:n((()=>[m(_(r(q)("recharge.text.tip2")),1)])),_:1}),s(x,{class:"mt-20 font-12-grey"},{default:n((()=>[m(_(r(q)("recharge.text.tip3")),1)])),_:1}),s(x,{class:"mt-20 font-12-grey"},{default:n((()=>[m(_(r(q)("recharge.text.tip4")),1)])),_:1})])),_:1})])),_:1})],64)}}}),[["__scopeId","data-v-e9282ec1"]]);export{q as default};
