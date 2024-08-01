import{R as e,aD as t,V as a,U as n,x as l,y as r,z as o,A as s,q as i,C as u,H as m,Y as c,Z as p,bW as f,I as d,J as y,bT as _,c2 as x,c3 as g,N as b}from"./index-DQ1rtinv.js";import{_ as P}from"./uni-nav-bar.DjBb34GI.js";import{r as h}from"./uni-app.es.Cjy5VdAU.js";import{_ as v}from"./uni-section.D3mqx96n.js";import{_ as w}from"./u-input.CXlVRvL9.js";import{_ as j,a as $}from"./u-form.C65TemM1.js";import{_ as T}from"./u-icon.CGC9KXiC.js";import{_ as k}from"./u-button.BJ1lx-r7.js";import{c as q,r as C}from"./wallet.Cyq-A9WD.js";import{_ as V}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CVC5mL0W.js";import"./uni-status-bar.CY3EcjAi.js";import"./u-line.B0mzGBy-.js";import"./u-loading-icon.CWztmby-.js";const U=V({__name:"user-center-recharge-scan-payment",props:{type:String,naviBarTitle:String},setup(V){const{t:U}=e(),R=V,A=t((()=>({navbarTitle:"wechatpay"===R.type?U("onlinePayment.text.wechatPay"):U("onlinePayment.text.aliPay"),contentTitle:"wechatpay"===R.type?U("onlinePayment.text.wechatPayTip"):U("onlinePayment.text.aliPayTip")}))),E=a(),F=n({money:"",transfer_username:""}),H={money:{type:"string",required:!0,message:U("onlinePayment.text.transferAmountErrorTip"),trigger:["blur","change"]},transfer_username:{type:"string",required:!0,message:U("onlinePayment.text.transferUserErrorTip"),trigger:["blur","change"]}},I=a({});async function L(){if(console.log("触发了",F.money),I.value.max_money<parseFloat(F.money))i({title:"充值金额已超过最大可充值金额"+I.value.max_money+"，请重新输入",icon:"none"});else try{await E.value.validate(),x({title:U("common.text.systemHint"),content:U("onlinePayment.text.tip1"),success:async e=>{if(e.confirm){const{error:e,message:t,data:a}=await C({type:I.value.type,payment_id:I.value.id,transfer:"transfer",money:F.money,transfer_username:F.transfer_username});if(e)return void(await i({title:t,icon:"none"}));g({id:a.id})}}})}catch(e){e.length&&await i({title:e[0].message,icon:"none"})}}return async function(){const{error:e,message:t,data:a}=await q({type:R.type});e?await i({title:t,icon:"none"}):(I.value=a.list,console.log("data",a))}(),(e,t)=>{const a=h(l("uni-nav-bar"),P),n=b,i=u,x=h(l("uni-section"),v),g=h(l("up-input"),w),q=h(l("up-form-item"),j),C=h(l("up-icon"),T),V=h(l("up-form"),$),U=h(l("up-button"),k);return r(),o(i,null,{default:s((()=>[m(a,{title:A.value.navbarTitle,"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left","right-icon":"more",onClickLeft:c(p),onClickRight:c(f)},null,8,["title","onClickLeft","onClickRight"]),m(x,{title:e.$t("onlinePayment.text.scanCodeToPay"),type:"line"},{default:s((()=>[m(i,{class:"code-wrapper pb-30"},{default:s((()=>[m(n,{mode:"widthFix",onClick:t[0]||(t[0]=e=>{return t=I.value.qrcode_url,void _({urls:[t]});var t}),src:I.value.qrcode_url,style:{width:"500rpx"}},null,8,["src"]),m(i,{class:"font-16-red-bold mt-30"},{default:s((()=>[d(y(A.value.contentTitle),1)])),_:1}),m(i,{class:"instructions mt-30"},{default:s((()=>[m(i,null,{default:s((()=>[d(y(e.$t("onlinePayment.text.illustrate")),1)])),_:1}),m(i,{class:"instructions-content font-12"},{default:s((()=>[m(i,null,{default:s((()=>[d(y(e.$t("onlinePayment.text.tip2"))+y(I.value.max_money),1)])),_:1}),m(i,{class:"mt-10"},{default:s((()=>[d(y(e.$t("onlinePayment.text.tip3")),1)])),_:1}),m(i,{class:"mt-10"},{default:s((()=>[d(y(e.$t("onlinePayment.text.tip4")),1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1},8,["title"]),m(i,{style:{height:"20rpx","background-color":"#eeeeee"}}),m(x,{class:"mt-20",title:e.$t("onlinePayment.text.tip5"),type:"line"},{default:s((()=>[m(V,{class:"p-30",model:F,rules:H,"label-position":"top","label-width":"100",ref_key:"formRef",ref:E},{default:s((()=>[m(q,{label:e.$t("onlinePayment.text.transferAmount"),prop:"money",required:""},{default:s((()=>[m(g,{type:"number",placeholder:H.money.message,"prefix-icon":"rmb",clearable:"",modelValue:F.money,"onUpdate:modelValue":t[1]||(t[1]=e=>F.money=e)},null,8,["placeholder","modelValue"])])),_:1},8,["label"]),m(q,{label:e.$t("onlinePayment.text.transferUser"),prop:"transfer_username",required:""},{default:s((()=>[m(g,{type:"text",placeholder:H.transfer_username.message,clearable:"",modelValue:F.transfer_username,"onUpdate:modelValue":t[2]||(t[2]=e=>F.transfer_username=e)},{prefix:s((()=>[m(C,{name:"account"})])),_:1},8,["placeholder","modelValue"])])),_:1},8,["label"])])),_:1},8,["model"]),m(i,{class:"mt-10 p-30"},{default:s((()=>[m(U,{color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:L},null,8,["text"])])),_:1}),m(i,{class:"p-30"},{default:s((()=>[m(i,{class:"font-16"},{default:s((()=>[d(y(e.$t("onlinePayment.text.tip6")),1)])),_:1}),m(i,{class:"u-flex font-14-grey mt-20"},{default:s((()=>[m(i,null,{default:s((()=>[d("1.")])),_:1}),m(i,{class:"ml-10"},{default:s((()=>[d(y(e.$t("onlinePayment.text.tip7")),1)])),_:1})])),_:1}),m(i,{class:"u-flex font-14-grey mt-10"},{default:s((()=>[m(i,null,{default:s((()=>[d("2.")])),_:1}),m(i,{class:"ml-10"},{default:s((()=>[d(y(e.$t("onlinePayment.text.tip8")),1)])),_:1})])),_:1})])),_:1})])),_:1},8,["title"])])),_:1})}}},[["__scopeId","data-v-d9535d23"]]);export{U as default};
