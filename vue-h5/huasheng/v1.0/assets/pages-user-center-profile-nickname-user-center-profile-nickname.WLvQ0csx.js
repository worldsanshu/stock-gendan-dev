import{R as e,T as t,V as a,U as n,x as i,y as o,D as r,H as s,Y as l,A as m,F as c,C as u,Z as f,bL as p,q as d}from"./index-BWJszhAQ.js";import{_ as k}from"./uni-nav-bar.DYJ8qjGb.js";import{r as b}from"./uni-app.es.C_vI-sDC.js";import{_ as g}from"./u-input.DzA3kMud.js";import{_,a as j}from"./u-form.Cfd37Cd6.js";import{_ as x}from"./u-button.DvsaXey4.js";import{_ as N}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.7JXZxnLM.js";import"./uni-status-bar.NjuetTv4.js";import"./u-icon.DXT6fckP.js";import"./u-line.BacfgkrX.js";import"./u-loading-icon.DUFrNOOQ.js";const h=N(Object.assign({name:"user-center-profile-nickname"},{__name:"user-center-profile-nickname",setup(N){const{t:h}=e(),y=t(),v=a(),w=n({nickname:y.userinfo.nickname}),V={nickname:[{type:"string",message:h("nickName.text.enterNickName"),trigger:["blur"]},{max:10,message:h("nickName.text.tip1"),trigger:["blur","change"]}]};async function $(){try{await v.value.validate();const{error:e,message:t}=await p(w);if(await d({title:t,icon:e?"none":"success",duration:1e3}),e)return;await y.refreshUserinfo(),f()}catch(e){e.length&&await d({title:e[0].message,icon:"none"})}}return(e,t)=>{const a=b(i("uni-nav-bar"),k),n=b(i("up-input"),g),p=b(i("up-form-item"),_),d=b(i("up-form"),j),N=b(i("up-button"),x),h=u;return o(),r(c,null,[s(a,{title:e.$t("page.title.nickName"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:l(f)},null,8,["title","onClickLeft"]),s(h,{class:"body"},{default:m((()=>[s(d,{class:"form","label-width":"120",labelPosition:"top",model:w,rules:V,ref_key:"formRef",ref:v},{default:m((()=>[s(p,{"left-icon":"account-fill",label:e.$t("nickName.text.yourNickName"),prop:"nickname",borderBottom:"",ref:"item1"},{default:m((()=>[s(n,{modelValue:w.nickname,"onUpdate:modelValue":t[0]||(t[0]=e=>w.nickname=e),placeholder:e.$t("nickName.text.enterNickName"),focus:""},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),s(N,{class:"mt-100",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:$},null,8,["text"])])),_:1})],64)}}}),[["__scopeId","data-v-53dfc05e"]]);export{h as default};