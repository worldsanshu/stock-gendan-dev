import{R as e,aC as t,V as a,U as n,x as l,y as r,z as o,A as s,q as i,C as u,H as m,Y as c,Z as p,bY as f,I as d,J as y,bW as _,c4 as x,c5 as g,N as b}from"./index-_qVRbLMl.js";import{_ as v}from"./uni-nav-bar.CqtfX-i-.js";import{r as P}from"./uni-app.es.CwLmRD9h.js";import{_ as h}from"./uni-section.DcNh2fp6.js";import{_ as w}from"./u-input.CrhwEoxg.js";import{_ as j,a as $}from"./u-form.D51hNWnV.js";import{_ as T}from"./u-icon.WTlR_o2P.js";import{_ as k}from"./u-button.BhmJCeJ7.js";import{c as C,r as q}from"./wallet.CrnvYlvL.js";import{_ as V}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CkUbBeV1.js";import"./uni-status-bar.BBpSB2OF.js";import"./u-line.xBYwZJD9.js";import"./u-loading-icon.C0chiosN.js";const U=V({__name:"user-center-recharge-scan-payment",props:{type:String,naviBarTitle:String},setup(V){const{t:U}=e(),R=V,A=t((()=>({navbarTitle:"wechatpay"===R.type?U("onlinePayment.text.wechatPay"):U("onlinePayment.text.aliPay"),contentTitle:"wechatpay"===R.type?U("onlinePayment.text.wechatPayTip"):U("onlinePayment.text.aliPayTip")}))),E=a(),H=n({money:"",transfer_username:""}),I={money:{type:"string",required:!0,message:U("onlinePayment.text.transferAmountErrorTip"),trigger:["blur","change"]},transfer_username:{type:"string",required:!0,message:U("onlinePayment.text.transferUserErrorTip"),trigger:["blur","change"]}},Y=a({});async function B(){if(console.log("触发了",H.money),Y.value.max_money<parseFloat(H.money))i({title:"充值金额已超过最大可充值金额"+Y.value.max_money+"，请重新输入",icon:"none"});else try{await E.value.validate(),x({title:U("common.text.systemHint"),content:U("onlinePayment.text.tip1"),success:async e=>{if(e.confirm){const{error:e,message:t,data:a}=await q({type:Y.value.type,payment_id:Y.value.id,transfer:"transfer",money:H.money,transfer_username:H.transfer_username});if(e)return void(await i({title:t,icon:"none"}));g({id:a.id})}}})}catch(e){e.length&&await i({title:e[0].message,icon:"none"})}}return async function(){const{error:e,message:t,data:a}=await C({type:R.type});e?await i({title:t,icon:"none"}):(Y.value=a.list,console.log("data",a))}(),(e,t)=>{const a=P(l("uni-nav-bar"),v),n=b,i=u,x=P(l("uni-section"),h),g=P(l("up-input"),w),C=P(l("up-form-item"),j),q=P(l("up-icon"),T),V=P(l("up-form"),$),U=P(l("up-button"),k);return r(),o(i,null,{default:s((()=>[m(a,{title:A.value.navbarTitle,"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left","right-icon":"more",fixed:"",onClickLeft:c(p),onClickRight:c(f)},null,8,["title","onClickLeft","onClickRight"]),m(x,{title:e.$t("onlinePayment.text.scanCodeToPay"),type:"line"},{default:s((()=>[m(i,{class:"code-wrapper pb-30"},{default:s((()=>[m(n,{mode:"widthFix",onClick:t[0]||(t[0]=e=>{return t=Y.value.qrcode_url,void _({urls:[t]});var t}),src:Y.value.qrcode_url,style:{width:"500rpx"}},null,8,["src"]),m(i,{class:"font-16-red-bold mt-30"},{default:s((()=>[d(y(A.value.contentTitle),1)])),_:1}),m(i,{class:"instructions mt-30"},{default:s((()=>[m(i,null,{default:s((()=>[d(y(e.$t("onlinePayment.text.illustrate")),1)])),_:1}),m(i,{class:"instructions-content font-12"},{default:s((()=>[m(i,null,{default:s((()=>[d(y(e.$t("onlinePayment.text.tip2"))+y(Y.value.max_money),1)])),_:1}),m(i,{class:"mt-10"},{default:s((()=>[d(y(e.$t("onlinePayment.text.tip3")),1)])),_:1}),m(i,{class:"mt-10"},{default:s((()=>[d(y(e.$t("onlinePayment.text.tip4")),1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1},8,["title"]),m(i,{style:{height:"20rpx","background-color":"#eeeeee"}}),m(x,{class:"mt-20",title:e.$t("onlinePayment.text.tip5"),type:"line"},{default:s((()=>[m(V,{class:"p-30",model:H,rules:I,"label-position":"top","label-width":"100",ref_key:"formRef",ref:E},{default:s((()=>[m(C,{label:e.$t("onlinePayment.text.transferAmount"),prop:"money",required:""},{default:s((()=>[m(g,{type:"number",placeholder:I.money.message,"prefix-icon":"rmb",clearable:"",modelValue:H.money,"onUpdate:modelValue":t[1]||(t[1]=e=>H.money=e)},null,8,["placeholder","modelValue"])])),_:1},8,["label"]),m(C,{label:e.$t("onlinePayment.text.transferUser"),prop:"transfer_username",required:""},{default:s((()=>[m(g,{type:"text",placeholder:I.transfer_username.message,clearable:"",modelValue:H.transfer_username,"onUpdate:modelValue":t[2]||(t[2]=e=>H.transfer_username=e)},{prefix:s((()=>[m(q,{name:"account"})])),_:1},8,["placeholder","modelValue"])])),_:1},8,["label"])])),_:1},8,["model"]),m(i,{class:"mt-10 p-30"},{default:s((()=>[m(U,{color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:B},null,8,["text"])])),_:1}),m(i,{class:"p-30"},{default:s((()=>[m(i,{class:"font-16"},{default:s((()=>[d(y(e.$t("onlinePayment.text.tip6")),1)])),_:1}),m(i,{class:"u-flex font-14-grey mt-20"},{default:s((()=>[m(i,null,{default:s((()=>[d("1.")])),_:1}),m(i,{class:"ml-10"},{default:s((()=>[d(y(e.$t("onlinePayment.text.tip7")),1)])),_:1})])),_:1}),m(i,{class:"u-flex font-14-grey mt-10"},{default:s((()=>[m(i,null,{default:s((()=>[d("2.")])),_:1}),m(i,{class:"ml-10"},{default:s((()=>[d(y(e.$t("onlinePayment.text.tip8")),1)])),_:1})])),_:1})])),_:1})])),_:1},8,["title"])])),_:1})}}},[["__scopeId","data-v-0c40556a"]]);export{U as default};