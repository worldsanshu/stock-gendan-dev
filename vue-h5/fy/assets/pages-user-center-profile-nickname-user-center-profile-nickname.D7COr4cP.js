import{R as e,T as t,V as a,U as n,x as i,D as o,H as r,Y as s,z as l,F as m,B as c,C as u,Z as p,aY as f,q as d}from"./index-hU8OKPmH.js";import{_ as k}from"./uni-nav-bar.U3Cmk77Z.js";import{r as b}from"./uni-app.es.DADla7ur.js";import{_ as g}from"./u-input.CvbCMzA9.js";import{_,a as j}from"./u-form.Cwk_KWaU.js";import{_ as x}from"./u-button.CBnbv77V.js";import{_ as N}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DgCYXD5d.js";import"./uni-status-bar.BC4SStPU.js";import"./u-icon.BX8bEo6J.js";import"./u-line.ILQrRGaQ.js";import"./u-loading-icon.DGLmxP69.js";const h=N(Object.assign({name:"user-center-profile-nickname"},{__name:"user-center-profile-nickname",setup(N){const{t:h}=e(),v=t(),w=a(),y=n({nickname:v.userinfo.nickname}),C={nickname:[{type:"string",message:h("nickName.text.enterNickName"),trigger:["blur"]},{max:10,message:h("nickName.text.tip1"),trigger:["blur","change"]}]};async function F(){try{await w.value.validate();const{error:e,message:t}=await f(y);if(await d({title:t,icon:e?"none":"success",duration:1e3}),e)return;await v.refreshUserinfo(),p()}catch(e){e.length&&await d({title:e[0].message,icon:"none"})}}return(e,t)=>{const a=b(i("uni-nav-bar"),k),n=b(i("up-input"),g),f=b(i("up-form-item"),_),d=b(i("up-form"),j),N=b(i("up-button"),x),h=c;return u(),o(m,null,[r(a,{title:e.$t("page.title.nickName"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:s(p)},null,8,["title","onClickLeft"]),r(h,{class:"body"},{default:l((()=>[r(d,{class:"form","label-width":"120",labelPosition:"top",model:y,rules:C,ref_key:"formRef",ref:w},{default:l((()=>[r(f,{"left-icon":"account-fill",label:e.$t("nickName.text.yourNickName"),prop:"nickname",borderBottom:"",ref:"item1"},{default:l((()=>[r(n,{modelValue:y.nickname,"onUpdate:modelValue":t[0]||(t[0]=e=>y.nickname=e),placeholder:e.$t("nickName.text.enterNickName"),focus:""},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),r(N,{class:"mt-100",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:F},null,8,["text"])])),_:1})],64)}}}),[["__scopeId","data-v-53dfc05e"]]);export{h as default};