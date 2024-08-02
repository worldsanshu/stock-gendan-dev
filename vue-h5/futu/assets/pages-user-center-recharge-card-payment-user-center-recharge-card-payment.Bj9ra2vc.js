import{R as e,V as t,U as a,x as r,y as n,z as o,A as l,q as i,C as s,H as u,Y as c,Z as m,bW as d,I as f,J as p,bI as b,r as _,c2 as g,c3 as y,O as h}from"./index-BKBmZM5g.js";import{_ as x}from"./uni-nav-bar.DQSGXMB-.js";import{r as k}from"./uni-app.es.CT9bmSuD.js";import{_ as C}from"./uni-card.BLvvm0pt.js";import{_ as v}from"./uni-icons.o26QNkQn.js";import{_ as R,a as j}from"./uni-list.DOkmdB_E.js";import{_ as $}from"./uni-section.PH4E6Z4V.js";import{_ as w}from"./u-input.Dg8GBcSs.js";import{_ as V,a as q}from"./u-form.C9P8YSaz.js";import{_ as N}from"./u-button.CPApju8R.js";import{e as T,r as A}from"./wallet.Ck02CzmG.js";import{_ as E}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.CrgiwTPU.js";import"./uni-badge.21OXFeuE.js";import"./u-icon.ChK8Jwfk.js";import"./u-line.DE4nbZs9.js";import"./u-loading-icon.BDLNhgIp.js";const I=E({__name:"user-center-recharge-card-payment",setup(E){const{t:I}=e(),U=t(),F=a({money:0,transfer_number:""}),H={money:{type:"string",required:!0,message:I("bankCardRecharge.text.transferAmountErrorTip"),trigger:["blur","change"]},transfer_number:[{type:"string",required:!0,message:I("bankCardRecharge.text.transferCardNumberErrorTip1"),trigger:["blur","change"]},{validator:(e,t,a)=>{if(/^[0-9]{16,19}$/.test(t))return!0;a(I("bankCardRecharge.text.transferCardNumberErrorTip2"))},trigger:["change","blur"]}]},L=t({});async function O(){if(console.log("触发了0",L.value.max_money),console.log("触发了1",F.money),L.value.max_money<parseFloat(F.money))return console.log("触发了2"),void i({title:"充值金额已超过最大可充值金额"+L.value.max_money+"，请重新输入",icon:"none"});console.log("触发了3");try{await U.value.validate(),g({title:I("common.text.systemHint"),content:I("bankCardRecharge.text.tip4"),success:async e=>{if(e.confirm){const{error:e,message:t,data:a}=await A({type:"bank",payment_id:L.value.id,transfer:"transfer",money:F.money,transfer_number:F.transfer_number});if(e)return void(await i({title:t,icon:"none"}));y({id:a.id})}}})}catch(e){e.length&&await i({title:e[0].message,icon:"none"})}}return async function(){const{error:e,message:t,data:a}=await T();e?await i({title:t,icon:"none"}):(L.value=a.list,console.log("data",a))}(),(e,t)=>{const a=k(r("uni-nav-bar"),x),i=h,g=k(r("uni-card"),C),y=s,T=k(r("uni-icons"),v),A=k(r("uni-list-item"),R),E=k(r("uni-list"),j),I=k(r("uni-section"),$),P=k(r("up-input"),w),W=k(r("up-form-item"),V),Z=k(r("up-form"),q),z=k(r("up-button"),N);return n(),o(y,null,{default:l((()=>[u(a,{title:e.$t("bankCardRecharge.text.title"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left","right-icon":"more",onClickLeft:c(m),onClickRight:c(d)},null,8,["title","onClickLeft","onClickRight"]),u(g,{class:"mt-20",title:e.$t("bankCardRecharge.text.unionPayPayment"),"is-full":"","is-shadow":!1,thumbnail:"/static/images/union-pay.png"},{default:l((()=>[u(i,null,{default:l((()=>[f(p(e.$t("bankCardRecharge.text.arrivalTime")),1)])),_:1})])),_:1},8,["title"]),u(I,{class:"mt-20",title:e.$t("bankCardRecharge.text.receivingAccount"),type:"line"},{default:l((()=>[u(E,null,{default:l((()=>[u(A,null,{header:l((()=>[u(y,{class:"list-item-header"},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.payee")),1)])),_:1})])),body:l((()=>[u(y,{class:"list-item-body"},{default:l((()=>[f(p(L.value.payee),1)])),_:1})])),footer:l((()=>[u(T,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[0]||(t[0]=e=>c(b)(L.value.payee))})])),_:1}),u(A,null,{header:l((()=>[u(y,{class:"list-item-header"},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.cardNumber")),1)])),_:1})])),body:l((()=>[u(y,{class:"list-item-body"},{default:l((()=>[f(p(L.value.card_number),1)])),_:1})])),footer:l((()=>[u(T,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[1]||(t[1]=e=>c(b)(L.value.card_number))})])),_:1}),u(A,null,{header:l((()=>[u(y,{class:"list-item-header"},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.bank")),1)])),_:1})])),body:l((()=>[u(y,{class:"list-item-body"},{default:l((()=>[f(p(L.value.bank_name),1)])),_:1})])),footer:l((()=>[u(T,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[2]||(t[2]=e=>c(b)(L.value.bank_name))})])),_:1})])),_:1})])),_:1},8,["title"]),u(I,{class:"mt-20",title:e.$t("bankCardRecharge.text.tip1"),type:"line"},{default:l((()=>[u(Z,{class:"p-30",model:F,rules:H,"label-position":"top","label-width":"100",ref_key:"formRef",ref:U},{default:l((()=>[u(W,{label:e.$t("bankCardRecharge.text.transferAmount"),prop:"money",required:""},{default:l((()=>[u(P,{type:"number","prefix-icon":"rmb",clearable:"",modelValue:F.money,"onUpdate:modelValue":t[3]||(t[3]=e=>F.money=e)},null,8,["modelValue"])])),_:1},8,["label"]),u(W,{label:e.$t("bankCardRecharge.text.transferCardNumber"),prop:"transfer_number",required:""},{default:l((()=>[u(P,{type:"text",clearable:"",modelValue:F.transfer_number,"onUpdate:modelValue":t[4]||(t[4]=e=>F.transfer_number=e)},{prefix:l((()=>[u(T,{"custom-prefix":"fc-iconfont",type:"icon-bankcard"})])),_:1},8,["modelValue"])])),_:1},8,["label"])])),_:1},8,["model"]),u(y,{class:"mt-10 p-30"},{default:l((()=>[u(z,{color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:O},null,8,["text"])])),_:1}),u(y,{class:"center-center mt-10 p-30",onClick:t[5]||(t[5]=e=>async function(e){console.log("110"),_({url:e})}("/pages/customer-service/customer-service"))},{default:l((()=>[u(i,null,{default:l((()=>[f(p(e.$t("bankCardRecharge.text.tip2")),1)])),_:1}),u(i,{style:{color:"#E5574F"}},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.tip3")),1)])),_:1})])),_:1})])),_:1},8,["title"])])),_:1})}}},[["__scopeId","data-v-df6a320f"]]);export{I as default};
