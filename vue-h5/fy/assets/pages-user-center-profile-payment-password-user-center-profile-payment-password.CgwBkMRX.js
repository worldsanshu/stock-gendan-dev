import{R as e,V as t,U as o,x as r,y as a,D as s,H as l,Y as d,A as p,F as n,C as i,Z as u,bU as m,q as w}from"./index-CRttwwij.js";import{_ as c}from"./uni-nav-bar.QMl_o85J.js";import{r as b}from"./uni-app.es.otF8raCd.js";import{_ as f}from"./u-input.Dk95kOEY.js";import{_ as g,a as y}from"./u-form.MmCO-oEw.js";import{_}from"./u-button.DTOkec6s.js";import{_ as h}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.Bz4Sh8gD.js";import"./uni-status-bar.B9okywpV.js";import"./u-icon.CVzxT_4w.js";import"./u-line.C5lrB_C7.js";import"./u-loading-icon.Cvek95z1.js";const x=h({__name:"user-center-profile-payment-password",setup(h){const{t:x}=e(),P=t(),j=o({oldpwd:"",newpwd:"",subpwd:""}),V={oldpwd:{type:"string",required:!0,message:x("paymentPassword.text.errorTip1"),trigger:["blur","change"]},newpwd:{type:"string",required:!0,message:x("paymentPassword.text.errorTip2"),trigger:["blur","change"]},subpwd:[{type:"string",required:!0,message:x("paymentPassword.text.errorTip3"),trigger:["blur","change"]},{validator:(e,t)=>t===j.newpwd,message:x("paymentPassword.text.errorTip4"),trigger:["change","blur"]}]};async function k(){try{await P.value.validate();const e=await m(j);if(w({title:e.message,icon:"none",duration:1e3}),e.error)return;u()}catch(e){e.length&&w({title:e[0].message,icon:"none"})}}return(e,t)=>{const o=b(r("uni-nav-bar"),c),m=b(r("up-input"),f),w=b(r("up-form-item"),g),h=b(r("up-form"),y),x=b(r("up-button"),_),q=i;return a(),s(n,null,[l(o,{title:e.$t("page.title.paymentPassword"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:d(u)},null,8,["title","onClickLeft"]),l(q,{class:"body"},{default:p((()=>[l(h,{class:"form","label-width":"120",labelPosition:"top",model:j,rules:V,ref_key:"formRef",ref:P},{default:p((()=>[l(w,{required:"","left-icon":"lock",label:e.$t("paymentPassword.text.oldPassword"),prop:"oldpwd",borderBottom:""},{default:p((()=>[l(m,{type:"password",modelValue:j.oldpwd,"onUpdate:modelValue":t[0]||(t[0]=e=>j.oldpwd=e),placeholder:V.oldpwd.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),l(w,{required:"","left-icon":"lock",label:e.$t("paymentPassword.text.newPassword"),prop:"newpwd",borderBottom:""},{default:p((()=>[l(m,{type:"password",modelValue:j.newpwd,"onUpdate:modelValue":t[1]||(t[1]=e=>j.newpwd=e),placeholder:V.newpwd.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),l(w,{required:"","left-icon":"lock",label:e.$t("paymentPassword.text.confirmPassword"),prop:"subpwd",borderBottom:""},{default:p((()=>[l(m,{type:"password",modelValue:j.subpwd,"onUpdate:modelValue":t[2]||(t[2]=e=>j.subpwd=e),placeholder:V.subpwd[0].message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),l(x,{class:"mt-100",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:k},null,8,["text"])])),_:1})],64)}}},[["__scopeId","data-v-2aa256da"]]);export{x as default};