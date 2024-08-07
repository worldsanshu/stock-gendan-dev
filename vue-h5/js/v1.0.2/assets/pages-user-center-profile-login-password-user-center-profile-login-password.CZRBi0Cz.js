import{R as e,T as o,V as r,U as t,x as a,y as l,D as s,H as d,Y as i,A as n,F as p,C as u,Z as m,bK as c,q as w}from"./index-CUCz9quq.js";import{_ as g}from"./uni-nav-bar.B5kmKt-e.js";import{r as b}from"./uni-app.es.B7l1sWMt.js";import{_ as f}from"./u-input.bBUu6_D_.js";import{_,a as x}from"./u-form.uPLdZJMG.js";import{_ as h}from"./u-button.Cc01_ebD.js";import{_ as j}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.AC3xlVii.js";import"./uni-status-bar.BnrCe1qF.js";import"./u-icon.C7YV5ppE.js";import"./u-line.DX81QOA9.js";import"./u-loading-icon.Dqx9PYiW.js";const P=j(Object.assign({name:"user-center-profile-login-password"},{__name:"user-center-profile-login-password",setup(j){const{t:P}=e(),V=o(),y=r(),k=t({oldpwd:"",newpwd:"",subpwd:""}),v={oldpwd:{type:"string",required:!0,message:P("loginPassword.text.errorTip1"),trigger:["blur","change"]},newpwd:{type:"string",required:!0,message:P("loginPassword.text.errorTip2"),trigger:["blur","change"]},subpwd:[{type:"string",required:!0,message:P("loginPassword.text.errorTip3"),trigger:["blur","change"]},{validator:(e,o)=>o===k.newpwd,message:P("loginPassword.text.errorTip4"),trigger:["change","blur"]}]};async function q(){try{await y.value.validate();const e=await c(k);if(e.error)return void w({title:e.message,icon:"none",duration:1e3});w({title:"修改成功，请重新登录",icon:"none",duration:1e3,mask:!0}),setTimeout((()=>{V.logout()}),1500)}catch(e){e.length&&await w({title:e[0].message,icon:"none"})}}return(e,o)=>{const r=b(a("uni-nav-bar"),g),t=b(a("up-input"),f),c=b(a("up-form-item"),_),w=b(a("up-form"),x),j=b(a("up-button"),h),P=u;return l(),s(p,null,[d(r,{title:e.$t("page.title.loginPassword"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:i(m)},null,8,["title","onClickLeft"]),d(P,{class:"body"},{default:n((()=>[d(w,{class:"form","label-width":"120",labelPosition:"top",model:k,rules:v,ref_key:"formRef",ref:y},{default:n((()=>[d(c,{required:"","left-icon":"lock",label:e.$t("loginPassword.text.oldPassword"),prop:"oldpwd",borderBottom:""},{default:n((()=>[d(t,{modelValue:k.oldpwd,"onUpdate:modelValue":o[0]||(o[0]=e=>k.oldpwd=e),placeholder:v.oldpwd.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),d(c,{required:"","left-icon":"lock",label:e.$t("loginPassword.text.newPassword"),prop:"newpwd",borderBottom:""},{default:n((()=>[d(t,{type:"password",modelValue:k.newpwd,"onUpdate:modelValue":o[1]||(o[1]=e=>k.newpwd=e),placeholder:v.newpwd.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),d(c,{required:"","left-icon":"lock",label:e.$t("loginPassword.text.confirmPassword"),prop:"subpwd",borderBottom:""},{default:n((()=>[d(t,{type:"password",modelValue:k.subpwd,"onUpdate:modelValue":o[2]||(o[2]=e=>k.subpwd=e),placeholder:v.subpwd[0].message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),d(j,{class:"mt-100",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:q},null,8,["text"])])),_:1})],64)}}}),[["__scopeId","data-v-ee5dabc1"]]);export{P as default};
