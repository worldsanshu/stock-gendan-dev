import{R as e,V as o,U as r,x as t,y as a,D as l,H as s,Y as d,A as i,F as n,C as p,Z as u,bK as m,q as w}from"./index-Br34eEhS.js";import{_ as c}from"./uni-nav-bar.V8U-xaMC.js";import{r as g}from"./uni-app.es.-KBXnIhR.js";import{_ as f}from"./u-input.Ch559noI.js";import{_ as b,a as _}from"./u-form.DlZzolMQ.js";import{_ as x}from"./u-button.DC7gEp9a.js";import{_ as h}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DfW4o02-.js";import"./uni-status-bar.CFG8IPV-.js";import"./u-icon.B55BycVW.js";import"./u-line.B9GtlKhw.js";import"./u-loading-icon.DBZG26k-.js";const j=h(Object.assign({name:"user-center-profile-login-password"},{__name:"user-center-profile-login-password",setup(h){const{t:j}=e(),P=o(),V=r({oldpwd:"",newpwd:"",subpwd:""}),y={oldpwd:{type:"string",required:!0,message:j("loginPassword.text.errorTip1"),trigger:["blur","change"]},newpwd:{type:"string",required:!0,message:j("loginPassword.text.errorTip2"),trigger:["blur","change"]},subpwd:[{type:"string",required:!0,message:j("loginPassword.text.errorTip3"),trigger:["blur","change"]},{validator:(e,o)=>o===V.newpwd,message:j("loginPassword.text.errorTip4"),trigger:["change","blur"]}]};async function k(){try{await P.value.validate();const e=await m(V);if(await w({title:e.message,icon:"none",duration:1e3}),e.error)return;u()}catch(e){e.length&&await w({title:e[0].message,icon:"none"})}}return(e,o)=>{const r=g(t("uni-nav-bar"),c),m=g(t("up-input"),f),w=g(t("up-form-item"),b),h=g(t("up-form"),_),j=g(t("up-button"),x),q=p;return a(),l(n,null,[s(r,{title:e.$t("page.title.loginPassword"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:d(u)},null,8,["title","onClickLeft"]),s(q,{class:"body"},{default:i((()=>[s(h,{class:"form","label-width":"120",labelPosition:"top",model:V,rules:y,ref_key:"formRef",ref:P},{default:i((()=>[s(w,{required:"","left-icon":"lock",label:e.$t("loginPassword.text.oldPassword"),prop:"oldpwd",borderBottom:""},{default:i((()=>[s(m,{modelValue:V.oldpwd,"onUpdate:modelValue":o[0]||(o[0]=e=>V.oldpwd=e),placeholder:y.oldpwd.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),s(w,{required:"","left-icon":"lock",label:e.$t("loginPassword.text.newPassword"),prop:"newpwd",borderBottom:""},{default:i((()=>[s(m,{type:"password",modelValue:V.newpwd,"onUpdate:modelValue":o[1]||(o[1]=e=>V.newpwd=e),placeholder:y.newpwd.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),s(w,{required:"","left-icon":"lock",label:e.$t("loginPassword.text.confirmPassword"),prop:"subpwd",borderBottom:""},{default:i((()=>[s(m,{type:"password",modelValue:V.subpwd,"onUpdate:modelValue":o[2]||(o[2]=e=>V.subpwd=e),placeholder:y.subpwd[0].message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),s(j,{class:"mt-100",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:k},null,8,["text"])])),_:1})],64)}}}),[["__scopeId","data-v-aff15f6a"]]);export{j as default};
