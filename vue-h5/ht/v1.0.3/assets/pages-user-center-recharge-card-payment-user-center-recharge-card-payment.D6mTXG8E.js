import{R as e,V as t,U as a,x as r,y as n,z as o,A as l,q as i,C as s,H as u,Y as c,Z as m,bW as d,I as f,J as p,bI as b,r as _,c2 as g,c3 as y,O as h}from"./index-BSZMSohE.js";import{_ as x}from"./uni-nav-bar.DaV7kknO.js";import{r as k}from"./uni-app.es.xTxZrAO-.js";import{_ as C}from"./uni-card.W8qPSQXN.js";import{_ as v}from"./uni-icons.UvJfiB1T.js";import{_ as R,a as j}from"./uni-list.BAmADFzC.js";import{_ as $}from"./uni-section.DL9TJJdr.js";import{_ as w}from"./u-input.Cv139uxi.js";import{_ as V,a as q}from"./u-form.BetUNbkv.js";import{_ as A}from"./u-button.Bc9J1urq.js";import{e as E,r as N}from"./wallet.BMFo6IIZ.js";import{_ as T}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.DM8Vigxm.js";import"./uni-badge.D3JSGdBF.js";import"./u-icon.rSo6jUIp.js";import"./u-line.Bg8FUxRG.js";import"./u-loading-icon.oQN9K4Zk.js";const U=T({__name:"user-center-recharge-card-payment",setup(T){const{t:U}=e(),I=t(),P=a({money:0,transfer_number:""}),F={money:{type:"string",required:!0,message:U("bankCardRecharge.text.transferAmountErrorTip"),trigger:["blur","change"]},transfer_number:[{type:"string",required:!0,message:U("bankCardRecharge.text.transferCardNumberErrorTip1"),trigger:["blur","change"]},{validator:(e,t,a)=>{if(/^[0-9]{16,19}$/.test(t))return!0;a(U("bankCardRecharge.text.transferCardNumberErrorTip2"))},trigger:["change","blur"]}]},H=t({});async function L(){if(console.log("触发了0",H.value.max_money),console.log("触发了1",P.money),H.value.max_money<parseFloat(P.money))return console.log("触发了2"),void i({title:"充值金额已超过最大可充值金额"+H.value.max_money+"，请重新输入",icon:"none"});console.log("触发了3");try{await I.value.validate(),g({title:U("common.text.systemHint"),content:U("bankCardRecharge.text.tip4"),success:async e=>{if(e.confirm){const{error:e,message:t,data:a}=await N({type:"bank",payment_id:H.value.id,transfer:"transfer",money:P.money,transfer_number:P.transfer_number});if(e)return void(await i({title:t,icon:"none"}));y({id:a.id})}}})}catch(e){e.length&&await i({title:e[0].message,icon:"none"})}}return async function(){const{error:e,message:t,data:a}=await E();e?await i({title:t,icon:"none"}):(H.value=a.list,console.log("data",a))}(),(e,t)=>{const a=k(r("uni-nav-bar"),x),i=h,g=k(r("uni-card"),C),y=s,E=k(r("uni-icons"),v),N=k(r("uni-list-item"),R),T=k(r("uni-list"),j),U=k(r("uni-section"),$),O=k(r("up-input"),w),W=k(r("up-form-item"),V),z=k(r("up-form"),q),J=k(r("up-button"),A);return n(),o(y,null,{default:l((()=>[u(a,{title:e.$t("bankCardRecharge.text.title"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left","right-icon":"more",onClickLeft:c(m),onClickRight:c(d)},null,8,["title","onClickLeft","onClickRight"]),u(g,{class:"mt-20",title:e.$t("bankCardRecharge.text.unionPayPayment"),"is-full":"","is-shadow":!1,thumbnail:"/static/images/union-pay.png"},{default:l((()=>[u(i,null,{default:l((()=>[f(p(e.$t("bankCardRecharge.text.arrivalTime")),1)])),_:1})])),_:1},8,["title"]),u(U,{class:"mt-20",title:e.$t("bankCardRecharge.text.receivingAccount"),type:"line"},{default:l((()=>[u(T,null,{default:l((()=>[u(N,null,{header:l((()=>[u(y,{class:"list-item-header"},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.payee")),1)])),_:1})])),body:l((()=>[u(y,{class:"list-item-body"},{default:l((()=>[f(p(H.value.payee),1)])),_:1})])),footer:l((()=>[u(E,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[0]||(t[0]=e=>c(b)(H.value.payee))})])),_:1}),u(N,null,{header:l((()=>[u(y,{class:"list-item-header"},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.cardNumber")),1)])),_:1})])),body:l((()=>[u(y,{class:"list-item-body"},{default:l((()=>[f(p(H.value.card_number),1)])),_:1})])),footer:l((()=>[u(E,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[1]||(t[1]=e=>c(b)(H.value.card_number))})])),_:1}),u(N,null,{header:l((()=>[u(y,{class:"list-item-header"},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.bank")),1)])),_:1})])),body:l((()=>[u(y,{class:"list-item-body"},{default:l((()=>[f(p(H.value.bank_name),1)])),_:1})])),footer:l((()=>[u(E,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[2]||(t[2]=e=>c(b)(H.value.bank_name))})])),_:1})])),_:1})])),_:1},8,["title"]),u(U,{class:"mt-20",title:e.$t("bankCardRecharge.text.tip1"),type:"line"},{default:l((()=>[u(z,{class:"p-30",model:P,rules:F,"label-position":"top","label-width":"100",ref_key:"formRef",ref:I},{default:l((()=>[u(W,{label:e.$t("bankCardRecharge.text.transferAmount"),prop:"money",required:""},{default:l((()=>[u(O,{type:"number","prefix-icon":"rmb",clearable:"",modelValue:P.money,"onUpdate:modelValue":t[3]||(t[3]=e=>P.money=e)},null,8,["modelValue"])])),_:1},8,["label"]),u(W,{label:e.$t("bankCardRecharge.text.transferCardNumber"),prop:"transfer_number",required:""},{default:l((()=>[u(O,{type:"text",clearable:"",modelValue:P.transfer_number,"onUpdate:modelValue":t[4]||(t[4]=e=>P.transfer_number=e)},{prefix:l((()=>[u(E,{"custom-prefix":"fc-iconfont",type:"icon-bankcard"})])),_:1},8,["modelValue"])])),_:1},8,["label"])])),_:1},8,["model"]),u(y,{class:"mt-10 p-30"},{default:l((()=>[u(J,{color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:L},null,8,["text"])])),_:1}),u(y,{class:"center-center mt-10 p-30",onClick:t[5]||(t[5]=e=>async function(e){console.log("110"),_({url:e})}("/pages/customer-service/customer-service"))},{default:l((()=>[u(i,null,{default:l((()=>[f(p(e.$t("bankCardRecharge.text.tip2")),1)])),_:1}),u(i,{style:{color:"#E5574F"}},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.tip3")),1)])),_:1})])),_:1})])),_:1},8,["title"])])),_:1})}}},[["__scopeId","data-v-df6a320f"]]);export{U as default};
