import{R as e,V as t,U as a,x as r,y as n,z as o,A as l,q as i,C as s,H as u,Y as c,Z as m,bX as d,I as f,J as p,bJ as b,r as _,c3 as g,c4 as y,O as h}from"./index-DLU8qqff.js";import{_ as x}from"./uni-nav-bar.CoRAz90g.js";import{r as k}from"./uni-app.es.D9aFg0qL.js";import{_ as C}from"./uni-card.CXTphCNA.js";import{_ as v}from"./uni-icons.CHpq13ux.js";import{_ as R,a as j}from"./uni-list.Dn7jf5d9.js";import{_ as $}from"./uni-section.DduGaPvx.js";import{_ as w}from"./u-input.BSvGCxOR.js";import{_ as V,a as q}from"./u-form.BRj5kSiB.js";import{_ as N}from"./u-button.B345c9Fe.js";import{e as T,r as A}from"./wallet.D_z77eby.js";import{_ as E}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.BHywJPZs.js";import"./uni-badge.Wu4WXQTE.js";import"./u-icon.BAs1cCP0.js";import"./u-line.B-8l58Gh.js";import"./u-loading-icon.-zKdzUZA.js";const U=E({__name:"user-center-recharge-card-payment",setup(E){const{t:U}=e(),F=t(),H=a({money:0,transfer_number:""}),I={money:{type:"string",required:!0,message:U("bankCardRecharge.text.transferAmountErrorTip"),trigger:["blur","change"]},transfer_number:[{type:"string",required:!0,message:U("bankCardRecharge.text.transferCardNumberErrorTip1"),trigger:["blur","change"]},{validator:(e,t,a)=>{if(/^[0-9]{16,19}$/.test(t))return!0;a(U("bankCardRecharge.text.transferCardNumberErrorTip2"))},trigger:["change","blur"]}]},J=t({});async function L(){if(console.log("触发了0",J.value.max_money),console.log("触发了1",H.money),J.value.max_money<parseFloat(H.money))return console.log("触发了2"),void i({title:"充值金额已超过最大可充值金额"+J.value.max_money+"，请重新输入",icon:"none"});console.log("触发了3");try{await F.value.validate(),g({title:U("common.text.systemHint"),content:U("bankCardRecharge.text.tip4"),success:async e=>{if(e.confirm){const{error:e,message:t,data:a}=await A({type:"bank",payment_id:J.value.id,transfer:"transfer",money:H.money,transfer_number:H.transfer_number});if(e)return void(await i({title:t,icon:"none"}));y({id:a.id})}}})}catch(e){e.length&&await i({title:e[0].message,icon:"none"})}}return async function(){const{error:e,message:t,data:a}=await T();e?await i({title:t,icon:"none"}):(J.value=a.list,console.log("data",a))}(),(e,t)=>{const a=k(r("uni-nav-bar"),x),i=h,g=k(r("uni-card"),C),y=s,T=k(r("uni-icons"),v),A=k(r("uni-list-item"),R),E=k(r("uni-list"),j),U=k(r("uni-section"),$),O=k(r("up-input"),w),P=k(r("up-form-item"),V),Z=k(r("up-form"),q),z=k(r("up-button"),N);return n(),o(y,null,{default:l((()=>[u(a,{title:e.$t("bankCardRecharge.text.title"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left","right-icon":"more",onClickLeft:c(m),onClickRight:c(d)},null,8,["title","onClickLeft","onClickRight"]),u(g,{class:"mt-20",title:e.$t("bankCardRecharge.text.unionPayPayment"),"is-full":"","is-shadow":!1,thumbnail:"/static/images/union-pay.png"},{default:l((()=>[u(i,null,{default:l((()=>[f(p(e.$t("bankCardRecharge.text.arrivalTime")),1)])),_:1})])),_:1},8,["title"]),u(U,{class:"mt-20",title:e.$t("bankCardRecharge.text.receivingAccount"),type:"line"},{default:l((()=>[u(E,null,{default:l((()=>[u(A,null,{header:l((()=>[u(y,{class:"list-item-header"},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.payee")),1)])),_:1})])),body:l((()=>[u(y,{class:"list-item-body"},{default:l((()=>[f(p(J.value.payee),1)])),_:1})])),footer:l((()=>[u(T,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[0]||(t[0]=e=>c(b)(J.value.payee))})])),_:1}),u(A,null,{header:l((()=>[u(y,{class:"list-item-header"},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.cardNumber")),1)])),_:1})])),body:l((()=>[u(y,{class:"list-item-body"},{default:l((()=>[f(p(J.value.card_number),1)])),_:1})])),footer:l((()=>[u(T,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[1]||(t[1]=e=>c(b)(J.value.card_number))})])),_:1}),u(A,null,{header:l((()=>[u(y,{class:"list-item-header"},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.bank")),1)])),_:1})])),body:l((()=>[u(y,{class:"list-item-body"},{default:l((()=>[f(p(J.value.bank_name),1)])),_:1})])),footer:l((()=>[u(T,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[2]||(t[2]=e=>c(b)(J.value.bank_name))})])),_:1})])),_:1})])),_:1},8,["title"]),u(U,{class:"mt-20",title:e.$t("bankCardRecharge.text.tip1"),type:"line"},{default:l((()=>[u(Z,{class:"p-30",model:H,rules:I,"label-position":"top","label-width":"100",ref_key:"formRef",ref:F},{default:l((()=>[u(P,{label:e.$t("bankCardRecharge.text.transferAmount"),prop:"money",required:""},{default:l((()=>[u(O,{type:"number","prefix-icon":"rmb",clearable:"",modelValue:H.money,"onUpdate:modelValue":t[3]||(t[3]=e=>H.money=e)},null,8,["modelValue"])])),_:1},8,["label"]),u(P,{label:e.$t("bankCardRecharge.text.transferCardNumber"),prop:"transfer_number",required:""},{default:l((()=>[u(O,{type:"text",clearable:"",modelValue:H.transfer_number,"onUpdate:modelValue":t[4]||(t[4]=e=>H.transfer_number=e)},{prefix:l((()=>[u(T,{"custom-prefix":"fc-iconfont",type:"icon-bankcard"})])),_:1},8,["modelValue"])])),_:1},8,["label"])])),_:1},8,["model"]),u(y,{class:"mt-10 p-30"},{default:l((()=>[u(z,{color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:L},null,8,["text"])])),_:1}),u(y,{class:"center-center mt-10 p-30",onClick:t[5]||(t[5]=e=>async function(e){console.log("110"),_({url:e})}("/pages/customer-service/customer-service"))},{default:l((()=>[u(i,null,{default:l((()=>[f(p(e.$t("bankCardRecharge.text.tip2")),1)])),_:1}),u(i,{style:{color:"#E5574F"}},{default:l((()=>[f(p(e.$t("bankCardRecharge.text.tip3")),1)])),_:1})])),_:1})])),_:1},8,["title"])])),_:1})}}},[["__scopeId","data-v-eda4767a"]]);export{U as default};
