import{R as e,V as t,U as a,x as r,y as n,z as o,q as i,B as l,C as s,H as u,Y as c,Z as m,bY as d,I as f,J as p,aV as b,r as _,c4 as g,c5 as y,O as h}from"./index-DugkB_-4.js";import{_ as x}from"./uni-nav-bar.DGUi_IvM.js";import{r as k}from"./uni-app.es.CX-CC-lz.js";import{_ as C}from"./uni-card.DfMJ9tqH.js";import{_ as v}from"./uni-icons.D9BxN9k6.js";import{_ as R,a as j}from"./uni-list.Di66fBPU.js";import{_ as $}from"./uni-section.CUvr6xp9.js";import{_ as w}from"./u-input.UpaywbSH.js";import{_ as V,a as q}from"./u-form.MzjK-z7U.js";import{_ as E}from"./u-button.D_9ELcMi.js";import{e as N,r as T}from"./wallet.BMBhKyJb.js";import{_ as A}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.D_XiF9iL.js";import"./uni-badge.veoJrIAT.js";import"./u-icon.Ca1c56qc.js";import"./u-line.BpwwmqXn.js";import"./u-loading-icon.CCS9DSWM.js";const H=A({__name:"user-center-recharge-card-payment",setup(A){const{t:H}=e(),I=t(),U=a({money:0,transfer_number:""}),Y={money:{type:"string",required:!0,message:H("bankCardRecharge.text.transferAmountErrorTip"),trigger:["blur","change"]},transfer_number:[{type:"string",required:!0,message:H("bankCardRecharge.text.transferCardNumberErrorTip1"),trigger:["blur","change"]},{validator:(e,t,a)=>{if(/^[0-9]{16,19}$/.test(t))return!0;a(H("bankCardRecharge.text.transferCardNumberErrorTip2"))},trigger:["change","blur"]}]},B=t({});async function F(){if(console.log("触发了0",B.value.max_money),console.log("触发了1",U.money),B.value.max_money<parseFloat(U.money))return console.log("触发了2"),void i({title:"充值金额已超过最大可充值金额"+B.value.max_money+"，请重新输入",icon:"none"});console.log("触发了3");try{await I.value.validate(),g({title:H("common.text.systemHint"),content:H("bankCardRecharge.text.tip4"),success:async e=>{if(e.confirm){const{error:e,message:t,data:a}=await T({type:"bank",payment_id:B.value.id,transfer:"transfer",money:U.money,transfer_number:U.transfer_number});if(e)return void(await i({title:t,icon:"none"}));y({id:a.id})}}})}catch(e){e.length&&await i({title:e[0].message,icon:"none"})}}return async function(){const{error:e,message:t,data:a}=await N();e?await i({title:t,icon:"none"}):(B.value=a.list,console.log("data",a))}(),(e,t)=>{const a=k(r("uni-nav-bar"),x),i=h,g=k(r("uni-card"),C),y=l,N=k(r("uni-icons"),v),T=k(r("uni-list-item"),R),A=k(r("uni-list"),j),H=k(r("uni-section"),$),J=k(r("up-input"),w),L=k(r("up-form-item"),V),P=k(r("up-form"),q),Z=k(r("up-button"),E);return s(),n(y,null,{default:o((()=>[u(a,{title:e.$t("bankCardRecharge.text.title"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left","right-icon":"more",fixed:"",onClickLeft:c(m),onClickRight:c(d)},null,8,["title","onClickLeft","onClickRight"]),u(g,{class:"mt-20",title:e.$t("bankCardRecharge.text.unionPayPayment"),"is-full":"","is-shadow":!1,thumbnail:"/static/images/union-pay.png"},{default:o((()=>[u(i,null,{default:o((()=>[f(p(e.$t("bankCardRecharge.text.arrivalTime")),1)])),_:1})])),_:1},8,["title"]),u(H,{class:"mt-20",title:e.$t("bankCardRecharge.text.receivingAccount"),type:"line"},{default:o((()=>[u(A,null,{default:o((()=>[u(T,null,{header:o((()=>[u(y,{class:"list-item-header"},{default:o((()=>[f(p(e.$t("bankCardRecharge.text.payee")),1)])),_:1})])),body:o((()=>[u(y,{class:"list-item-body"},{default:o((()=>[f(p(B.value.payee),1)])),_:1})])),footer:o((()=>[u(N,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[0]||(t[0]=e=>c(b)(B.value.payee))})])),_:1}),u(T,null,{header:o((()=>[u(y,{class:"list-item-header"},{default:o((()=>[f(p(e.$t("bankCardRecharge.text.cardNumber")),1)])),_:1})])),body:o((()=>[u(y,{class:"list-item-body"},{default:o((()=>[f(p(B.value.card_number),1)])),_:1})])),footer:o((()=>[u(N,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[1]||(t[1]=e=>c(b)(B.value.card_number))})])),_:1}),u(T,null,{header:o((()=>[u(y,{class:"list-item-header"},{default:o((()=>[f(p(e.$t("bankCardRecharge.text.bank")),1)])),_:1})])),body:o((()=>[u(y,{class:"list-item-body"},{default:o((()=>[f(p(B.value.bank_name),1)])),_:1})])),footer:o((()=>[u(N,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[2]||(t[2]=e=>c(b)(B.value.bank_name))})])),_:1})])),_:1})])),_:1},8,["title"]),u(H,{class:"mt-20",title:e.$t("bankCardRecharge.text.tip1"),type:"line"},{default:o((()=>[u(P,{class:"p-30",model:U,rules:Y,"label-position":"top","label-width":"100",ref_key:"formRef",ref:I},{default:o((()=>[u(L,{label:e.$t("bankCardRecharge.text.transferAmount"),prop:"money",required:""},{default:o((()=>[u(J,{type:"number","prefix-icon":"rmb",clearable:"",modelValue:U.money,"onUpdate:modelValue":t[3]||(t[3]=e=>U.money=e)},null,8,["modelValue"])])),_:1},8,["label"]),u(L,{label:e.$t("bankCardRecharge.text.transferCardNumber"),prop:"transfer_number",required:""},{default:o((()=>[u(J,{type:"text",clearable:"",modelValue:U.transfer_number,"onUpdate:modelValue":t[4]||(t[4]=e=>U.transfer_number=e)},{prefix:o((()=>[u(N,{"custom-prefix":"fc-iconfont",type:"icon-bankcard"})])),_:1},8,["modelValue"])])),_:1},8,["label"])])),_:1},8,["model"]),u(y,{class:"mt-10 p-30"},{default:o((()=>[u(Z,{color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:F},null,8,["text"])])),_:1}),u(y,{class:"center-center mt-10 p-30",onClick:t[5]||(t[5]=e=>async function(e){console.log("110"),_({url:e})}("/pages/customer-service/customer-service"))},{default:o((()=>[u(i,null,{default:o((()=>[f(p(e.$t("bankCardRecharge.text.tip2")),1)])),_:1}),u(i,{style:{color:"#E5574F"}},{default:o((()=>[f(p(e.$t("bankCardRecharge.text.tip3")),1)])),_:1})])),_:1})])),_:1},8,["title"])])),_:1})}}},[["__scopeId","data-v-b2525925"]]);export{H as default};
