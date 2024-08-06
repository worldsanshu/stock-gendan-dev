import{R as e,V as t,U as o,x as r,y as a,D as s,H as l,Y as d,A as p,F as n,C as i,Z as u,b5 as m,q as w}from"./index-DxUBkcQO.js";import{_ as c}from"./uni-nav-bar.D3FG-aeM.js";import{r as b}from"./uni-app.es.WyEFFhdr.js";import{_ as f}from"./u-input.D3U0Ii1r.js";import{_ as g,a as y}from"./u-form.P7Q_1tQC.js";import{_}from"./u-button.B6sBVj6I.js";import{_ as x}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.BDom0B1X.js";import"./uni-status-bar.DTIfAn29.js";import"./u-icon.BCEdui8G.js";import"./u-line.BXTok7nE.js";import"./u-loading-icon.DIzsuW0j.js";const h=x({__name:"user-center-profile-payment-password",setup(x){const{t:h}=e(),j=t(),P=o({oldpwd:"",newpwd:"",subpwd:""}),V={oldpwd:{type:"string",required:!0,message:h("paymentPassword.text.errorTip1"),trigger:["blur","change"]},newpwd:{type:"string",required:!0,message:h("paymentPassword.text.errorTip2"),trigger:["blur","change"]},subpwd:[{type:"string",required:!0,message:h("paymentPassword.text.errorTip3"),trigger:["blur","change"]},{validator:(e,t)=>t===P.newpwd,message:h("paymentPassword.text.errorTip4"),trigger:["change","blur"]}]};async function k(){try{await j.value.validate();const e=await m(P);if(w({title:e.message,icon:"none",duration:1e3}),e.error)return;u()}catch(e){e.length&&w({title:e[0].message,icon:"none"})}}return(e,t)=>{const o=b(r("uni-nav-bar"),c),m=b(r("up-input"),f),w=b(r("up-form-item"),g),x=b(r("up-form"),y),h=b(r("up-button"),_),v=i;return a(),s(n,null,[l(o,{title:e.$t("page.title.paymentPassword"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:d(u)},null,8,["title","onClickLeft"]),l(v,{class:"body"},{default:p((()=>[l(x,{class:"form","label-width":"120",labelPosition:"top",model:P,rules:V,ref_key:"formRef",ref:j},{default:p((()=>[l(w,{required:"","left-icon":"lock",label:e.$t("paymentPassword.text.oldPassword"),prop:"oldpwd",borderBottom:""},{default:p((()=>[l(m,{type:"password",modelValue:P.oldpwd,"onUpdate:modelValue":t[0]||(t[0]=e=>P.oldpwd=e),placeholder:V.oldpwd.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),l(w,{required:"","left-icon":"lock",label:e.$t("paymentPassword.text.newPassword"),prop:"newpwd",borderBottom:""},{default:p((()=>[l(m,{type:"password",modelValue:P.newpwd,"onUpdate:modelValue":t[1]||(t[1]=e=>P.newpwd=e),placeholder:V.newpwd.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),l(w,{required:"","left-icon":"lock",label:e.$t("paymentPassword.text.confirmPassword"),prop:"subpwd",borderBottom:""},{default:p((()=>[l(m,{type:"password",modelValue:P.subpwd,"onUpdate:modelValue":t[2]||(t[2]=e=>P.subpwd=e),placeholder:V.subpwd[0].message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),l(h,{class:"mt-100",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:k},null,8,["text"])])),_:1})],64)}}},[["__scopeId","data-v-2aa256da"]]);export{h as default};