import{R as e,V as t,U as a,x as r,y as n,z as o,A as i,q as l,C as s,H as u,Y as c,Z as m,bY as d,I as f,J as p,aV as b,r as _,c4 as g,c5 as y,O as h}from"./index-C6SCDi5e.js";import{_ as x}from"./uni-nav-bar.Ck76H6Ry.js";import{r as k}from"./uni-app.es.B0qxMUeI.js";import{_ as C}from"./uni-card.CGP9MIuy.js";import{_ as v}from"./uni-icons.DHKS5643.js";import{_ as R,a as j}from"./uni-list.CgY7FO-m.js";import{_ as $}from"./uni-section.CAGTsIbm.js";import{_ as w}from"./u-input.D-mnXkYA.js";import{_ as V,a as q}from"./u-form.qlCX5ELJ.js";import{_ as E}from"./u-button.CGAXrbOY.js";import{e as A,r as N}from"./wallet.CO5xP1-C.js";import{_ as T}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.B9-k5eET.js";import"./uni-badge.Cm7GAJPf.js";import"./u-icon.DeyhXk6x.js";import"./u-line.B22hpcwN.js";import"./u-loading-icon.DlYeat6Y.js";const H=T({__name:"user-center-recharge-card-payment",setup(T){const{t:H}=e(),I=t(),U=a({money:0,transfer_number:""}),Y={money:{type:"string",required:!0,message:H("bankCardRecharge.text.transferAmountErrorTip"),trigger:["blur","change"]},transfer_number:[{type:"string",required:!0,message:H("bankCardRecharge.text.transferCardNumberErrorTip1"),trigger:["blur","change"]},{validator:(e,t,a)=>{if(/^[0-9]{16,19}$/.test(t))return!0;a(H("bankCardRecharge.text.transferCardNumberErrorTip2"))},trigger:["change","blur"]}]},F=t({});async function J(){if(console.log("触发了0",F.value.max_money),console.log("触发了1",U.money),F.value.max_money<parseFloat(U.money))return console.log("触发了2"),void l({title:"充值金额已超过最大可充值金额"+F.value.max_money+"，请重新输入",icon:"none"});console.log("触发了3");try{await I.value.validate(),g({title:H("common.text.systemHint"),content:H("bankCardRecharge.text.tip4"),success:async e=>{if(e.confirm){const{error:e,message:t,data:a}=await N({type:"bank",payment_id:F.value.id,transfer:"transfer",money:U.money,transfer_number:U.transfer_number});if(e)return void(await l({title:t,icon:"none"}));y({id:a.id})}}})}catch(e){e.length&&await l({title:e[0].message,icon:"none"})}}return async function(){const{error:e,message:t,data:a}=await A();e?await l({title:t,icon:"none"}):(F.value=a.list,console.log("data",a))}(),(e,t)=>{const a=k(r("uni-nav-bar"),x),l=h,g=k(r("uni-card"),C),y=s,A=k(r("uni-icons"),v),N=k(r("uni-list-item"),R),T=k(r("uni-list"),j),H=k(r("uni-section"),$),L=k(r("up-input"),w),P=k(r("up-form-item"),V),Z=k(r("up-form"),q),z=k(r("up-button"),E);return n(),o(y,null,{default:i((()=>[u(a,{title:e.$t("bankCardRecharge.text.title"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left","right-icon":"more",fixed:"",onClickLeft:c(m),onClickRight:c(d)},null,8,["title","onClickLeft","onClickRight"]),u(g,{class:"mt-20",title:e.$t("bankCardRecharge.text.unionPayPayment"),"is-full":"","is-shadow":!1,thumbnail:"/static/images/union-pay.png"},{default:i((()=>[u(l,null,{default:i((()=>[f(p(e.$t("bankCardRecharge.text.arrivalTime")),1)])),_:1})])),_:1},8,["title"]),u(H,{class:"mt-20",title:e.$t("bankCardRecharge.text.receivingAccount"),type:"line"},{default:i((()=>[u(T,null,{default:i((()=>[u(N,null,{header:i((()=>[u(y,{class:"list-item-header"},{default:i((()=>[f(p(e.$t("bankCardRecharge.text.payee")),1)])),_:1})])),body:i((()=>[u(y,{class:"list-item-body"},{default:i((()=>[f(p(F.value.payee),1)])),_:1})])),footer:i((()=>[u(A,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[0]||(t[0]=e=>c(b)(F.value.payee))})])),_:1}),u(N,null,{header:i((()=>[u(y,{class:"list-item-header"},{default:i((()=>[f(p(e.$t("bankCardRecharge.text.cardNumber")),1)])),_:1})])),body:i((()=>[u(y,{class:"list-item-body"},{default:i((()=>[f(p(F.value.card_number),1)])),_:1})])),footer:i((()=>[u(A,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[1]||(t[1]=e=>c(b)(F.value.card_number))})])),_:1}),u(N,null,{header:i((()=>[u(y,{class:"list-item-header"},{default:i((()=>[f(p(e.$t("bankCardRecharge.text.bank")),1)])),_:1})])),body:i((()=>[u(y,{class:"list-item-body"},{default:i((()=>[f(p(F.value.bank_name),1)])),_:1})])),footer:i((()=>[u(A,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[2]||(t[2]=e=>c(b)(F.value.bank_name))})])),_:1})])),_:1})])),_:1},8,["title"]),u(H,{class:"mt-20",title:e.$t("bankCardRecharge.text.tip1"),type:"line"},{default:i((()=>[u(Z,{class:"p-30",model:U,rules:Y,"label-position":"top","label-width":"100",ref_key:"formRef",ref:I},{default:i((()=>[u(P,{label:e.$t("bankCardRecharge.text.transferAmount"),prop:"money",required:""},{default:i((()=>[u(L,{type:"number","prefix-icon":"rmb",clearable:"",modelValue:U.money,"onUpdate:modelValue":t[3]||(t[3]=e=>U.money=e)},null,8,["modelValue"])])),_:1},8,["label"]),u(P,{label:e.$t("bankCardRecharge.text.transferCardNumber"),prop:"transfer_number",required:""},{default:i((()=>[u(L,{type:"text",clearable:"",modelValue:U.transfer_number,"onUpdate:modelValue":t[4]||(t[4]=e=>U.transfer_number=e)},{prefix:i((()=>[u(A,{"custom-prefix":"fc-iconfont",type:"icon-bankcard"})])),_:1},8,["modelValue"])])),_:1},8,["label"])])),_:1},8,["model"]),u(y,{class:"mt-10 p-30"},{default:i((()=>[u(z,{color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:J},null,8,["text"])])),_:1}),u(y,{class:"center-center mt-10 p-30",onClick:t[5]||(t[5]=e=>async function(e){console.log("110"),_({url:e})}("/pages/customer-service/customer-service"))},{default:i((()=>[u(l,null,{default:i((()=>[f(p(e.$t("bankCardRecharge.text.tip2")),1)])),_:1}),u(l,{style:{color:"#E5574F"}},{default:i((()=>[f(p(e.$t("bankCardRecharge.text.tip3")),1)])),_:1})])),_:1})])),_:1},8,["title"])])),_:1})}}},[["__scopeId","data-v-b2525925"]]);export{H as default};
