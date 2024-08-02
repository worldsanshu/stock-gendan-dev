import{R as e,V as t,x as a,y as l,D as i,H as s,Y as r,A as n,F as o,q as c,C as u,at as d,bW as p,I as m,bX as f,bY as g,z as h,J as _,bZ as y,G as k,b_ as b,b$ as v,E as C,r as x,O as j,c0 as w}from"./index-egBWsG2I.js";import{_ as z}from"./uni-nav-bar.cE7E8Cqa.js";import{d as O,r as I}from"./uni-app.es.Ch6kz4fv.js";import{_ as P}from"./uni-icons.D9kEH5_a.js";import{_ as R}from"./uni-card.D6b_nFHy.js";import{_ as T}from"./uni-section.Bx7vO_BH.js";import{_ as q,a as A}from"./uni-list.x6qKcD5O.js";import{_ as D}from"./u-skeleton.CKqKJqeM.js";import{a as J}from"./index.DY_m6FRZ.js";import{c as L}from"./wallet.Bc0qWbeN.js";import{_ as S}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.B9wGezOd.js";import"./uni-badge.Bmynedbj.js";const Y=S(Object.assign({name:"user-center-recharge"},{__name:"user-center-recharge",setup(S){const{t:Y}=e();let Z=t("transparent");const $=t([]),E=t([]),F=t({}),G=t(""),H=t(""),N=t(""),V=t(""),W=t(!0);async function X(){x({url:"/pages/customer-service/customer-service"})}return J().then((e=>{F.value=e.data.module})),O((e=>{e.scrollTop>20?Z.value="#ff7a4d":Z.value="transparent"})),async function(){const{error:e,message:t,data:a}=await L();if(e)await c({title:t,icon:"none"});else{for(const e of a.list)switch(e.type){case"currency":G.value="currency";break;case"bank":H.value="bank";break;case"wechatpay":N.value="wechatpay";break;case"alipay":V.value="alipay"}$.value=a.list.map((e=>e.type)),E.value=a.list.filter((e=>1===e.is_online)),W.value=!1}}(),(e,t)=>{const c=I(a("uni-nav-bar"),z),x=u,O=I(a("uni-icons"),P),J=j,L=I(a("uni-card"),R),S=I(a("uni-section"),T),$=I(a("uni-list-item"),q),B=I(a("uni-list"),A),K=I(a("up-skeleton"),D);return l(),i(o,null,[s(c,{title:"资金管理","status-bar":"","background-color":r(Z),color:"#fff","right-icon":"more-filled",fixed:"",border:!1,"left-icon":"left",onClickLeft:r(d),onClickRight:r(p)},null,8,["background-color","onClickLeft","onClickRight"]),s(x,{class:"page-bg"}),s(x,{class:"root"},{default:n((()=>[s(x,{class:"page-header"},{default:n((()=>[s(x,{class:"header-option"},{default:n((()=>[s(x,{class:"header-option-item header-option-item-active"},{default:n((()=>[m("充值")])),_:1}),s(x,{class:"header-option-item",onClick:r(f)},{default:n((()=>[m("提现")])),_:1},8,["onClick"]),s(x,{class:"header-option-item",onClick:r(g)},{default:n((()=>[m("钱包")])),_:1},8,["onClick"])])),_:1})])),_:1}),s(x,{class:"body"},{default:n((()=>[s(K,{rows:"3","rows-height":"150",title:!1,isArticle:!1,loading:W.value},{default:n((()=>[G.value?(l(),h(x,{key:0,onClick:r(y)},{default:n((()=>[s(S,{class:"mb-10 round-top",title:r(Y)("recharge.text.virtualCurrency"),type:"line"},{right:n((()=>[s(O,{type:"right",size:"20"})])),default:n((()=>[s(L,{class:"mt-20 round-bottom",title:r(Y)("recharge.text.virtualCurrency"),"is-full":"","is-shadow":!1,thumbnail:"/static/images/cryptocurrency-payment.png"},{default:n((()=>[s(J,null,{default:n((()=>[m(_(r(Y)("recharge.text.paymentDescription")),1)])),_:1})])),_:1},8,["title"])])),_:1},8,["title"])])),_:1},8,["onClick"])):k("",!0),H.value?(l(),h(x,{key:1,onClick:r(b)},{default:n((()=>[s(S,{class:"mt-20 round-top",title:r(Y)("recharge.text.bankCard"),type:"line"},{right:n((()=>[s(O,{type:"right",size:"20"})])),default:n((()=>[s(L,{class:"mt-20 round-bottom",title:r(Y)("recharge.text.bankCard"),"is-full":"","is-shadow":!1,thumbnail:"/static/images/union-pay.png"},{default:n((()=>[s(J,null,{default:n((()=>[m("信用卡、借记卡等银行卡充值方式")])),_:1})])),_:1},8,["title"])])),_:1},8,["title"])])),_:1},8,["onClick"])):k("",!0),N.value||V.value?(l(),h(x,{key:2},{default:n((()=>[s(S,{class:"mt-20 round-top",title:r(Y)("recharge.text.onlinePayment"),type:"line"},{default:n((()=>[s(B,{border:""},{default:n((()=>[N.value?(l(),h($,{key:0,thumb:"/static/images/weixin-pay.png",title:r(Y)("recharge.text.wechatPay"),clickable:"",link:"",onClick:t[0]||(t[0]=e=>r(v)({type:N.value}))},null,8,["title"])):k("",!0),V.value?(l(),h($,{key:1,thumb:"/static/images/zhifubao-pay.png",title:r(Y)("recharge.text.aliPay"),clickable:"",link:"",onClick:t[1]||(t[1]=e=>r(v)({type:"alipay"}))},null,8,["title"])):k("",!0)])),_:1})])),_:1},8,["title"])])),_:1})):k("",!0),E.value.length?(l(),h(x,{key:3},{default:n((()=>[s(S,{class:"mt-20 round-top",title:"三方钱包充值",type:"line"},{default:n((()=>[(l(!0),i(o,null,C(E.value,((e,t)=>(l(),h(B,{key:t,border:""},{default:n((()=>[s($,{thumb:e.logo_url,title:e.name,clickable:"",link:"",onClick:t=>r(w)({oItem:JSON.stringify(e)})},null,8,["thumb","title","onClick"])])),_:2},1024)))),128))])),_:1})])),_:1})):k("",!0),1==F.value.recharge_customer_service?(l(),h(S,{key:4,class:"mt-20 round-top",title:"线下充值请联系客服",type:"line"},{default:n((()=>[s(B,{border:""},{default:n((()=>[s($,{thumb:"/static/images/card-8.png",title:"在线客服",clickable:"",link:"",onClick:X})])),_:1})])),_:1})):k("",!0)])),_:1},8,["loading"]),s(x,{class:"mt-50 font-16-red-bold"},{default:n((()=>[m(_(r(Y)("recharge.text.tipTitle")),1)])),_:1}),s(x,{class:"mt-20 font-12-grey"},{default:n((()=>[m(_(r(Y)("recharge.text.tip1")),1)])),_:1}),s(x,{class:"mt-20 font-12-grey"},{default:n((()=>[m(_(r(Y)("recharge.text.tip2")),1)])),_:1}),s(x,{class:"mt-20 font-12-grey"},{default:n((()=>[m(_(r(Y)("recharge.text.tip3")),1)])),_:1}),s(x,{class:"mt-20 font-12-grey"},{default:n((()=>[m(_(r(Y)("recharge.text.tip4")),1)])),_:1})])),_:1})])),_:1})],64)}}}),[["__scopeId","data-v-e9282ec1"]]);export{Y as default};
