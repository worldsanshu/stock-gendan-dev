import{R as e,V as a,aD as t,U as n,x as l,y as o,D as r,H as i,Y as c,A as s,F as u,cf as d,q as m,cg as p,C as f,Z as b}from"./index-DHIuICwK.js";import{_ as g}from"./uni-nav-bar.Cpp9y_qD.js";import{r as x}from"./uni-app.es.d3PbdijJ.js";import{_ as v}from"./u-input.Dpa053Qf.js";import{_ as k,a as y}from"./u-form.CKviNZiv.js";import{_ as h}from"./u-button.IdAWSn9u.js";import{_ as C}from"./u-picker.CFI9mqIK.js";import{l as w}from"./wallet.zZ1Q1qmm.js";import{_ as T}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.COKLvYFy.js";import"./uni-status-bar.WgFv0Cjl.js";import"./u-icon.CwZ5kuOL.js";import"./u-line.BLprX7ly.js";import"./u-loading-icon.D2nqDR-a.js";import"./u-popup.C5uPDmGO.js";import"./u-transition.EyNGNlXc.js";import"./u-status-bar.BASBgPGq.js";const _=T(Object.assign({name:"user-center-wallet-management-add-bank"},{__name:"user-center-wallet-management-add-bank",setup(T){const{t:_}=e(),j=a([]),V=a([]),$=a([[]]),q=t((()=>[j.value,V.value])),B=a(!1),U=a(!1),R=a(!1),N=a(),O=n({card:"",province:"",city:"",bank:"",bankText:"",cityText:"",branch:""}),P={bank:{type:"string",required:!0,message:_("请选择开户银行"),trigger:["blur","change"]},card:[{type:"string",required:!0,message:_("addBankCard.text.tip1"),trigger:["blur","change"]},{validator:(e,a,t)=>{if(/^[0-9]{16,19}$/.test(a))return!0;t(_("addBankCard.text.tip2"))},trigger:["change","blur"]}],cityText:{type:"string",required:!0,message:_("addBankCard.text.tip3"),trigger:["blur","change"]},bankText:{type:"string",required:!0,message:_("addBankCard.text.tip4"),trigger:["blur","change"]},branch:{type:"string",required:!0,message:_("addBankCard.text.tip5"),trigger:["blur","change"]}};const A={};async function D(e){e.value[1]&&e.value[0].id===e.value[1].reid||(R.value=!0,await async function(e){if(A[e])return void(V.value=A[e]);const{error:a,data:t,message:n}=await d({reid:e});a?await m({title:n,icon:"none"}):(A[e]=t,V.value=t)}(e.value[0].id),R.value=!1)}function L(e){const[{name:a,id:t},{name:n,id:l}]=e.value;O.cityText=a===n?a:`${a}-${n}`,O.province=t,O.city=l,B.value=!1}function Y(e){O.bankText=e.value[0].name,O.bank=e.value[0].id,U.value=!1}async function Z(){try{await N.value.validate();const{error:e,message:a}=await w(O);await m({title:a,icon:e?"none":"success",duration:1e3}),e||setTimeout(b,1e3)}catch(e){e.length&&await m({title:e[0].message,icon:"none"})}}return async function(){const{error:e,data:a,message:t}=await d({reid:1});e?await m({title:t,icon:"none"}):j.value=a}(),async function(){const{error:e,message:a,data:t}=await p();e?await m({title:a,icon:"none"}):$.value=[t.bank]}(),(e,a)=>{const t=x(l("uni-nav-bar"),g),n=x(l("up-input"),v),d=x(l("up-form-item"),k),m=x(l("up-form"),y),p=x(l("up-button"),h),w=x(l("up-picker"),C),T=f;return o(),r(u,null,[i(t,{title:e.$t("page.title.addBankCard"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:c(b)},null,8,["title","onClickLeft"]),i(T,{class:"body"},{default:s((()=>[i(m,{"border-bottom":"",class:"form","label-width":"120",labelPosition:"top",model:O,rules:P,ref_key:"formRef",ref:N},{default:s((()=>[i(d,{required:"",label:e.$t("addBankCard.text.bankAccount"),prop:"bank"},{default:s((()=>[i(n,{type:"text",modelValue:O.bankText,"onUpdate:modelValue":a[0]||(a[0]=e=>O.bankText=e),placeholder:P.bankText.message,"disabled-color":"#fff","placeholder-style":"color: '#333'","suffix-icon":"arrow-down",onClick:a[1]||(a[1]=e=>U.value=!0)},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),i(d,{required:"",label:e.$t("addBankCard.text.bankCardNumber"),prop:"card"},{default:s((()=>[i(n,{type:"text",modelValue:O.card,"onUpdate:modelValue":a[2]||(a[2]=e=>O.card=e),placeholder:P.card[0].message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),i(d,{required:"",label:e.$t("addBankCard.text.accountOpeningCity"),prop:"cityText",onClick:a[4]||(a[4]=e=>B.value=!0)},{default:s((()=>[i(n,{type:"text",modelValue:O.cityText,"onUpdate:modelValue":a[3]||(a[3]=e=>O.cityText=e),placeholder:P.cityText.message,"disabled-color":"#fff","placeholder-style":"color: '#333'","suffix-icon":"arrow-down"},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),i(d,{required:"",label:e.$t("addBankCard.text.branchName"),prop:"branch"},{default:s((()=>[i(n,{type:"text",modelValue:O.branch,"onUpdate:modelValue":a[5]||(a[5]=e=>O.branch=e),placeholder:P.branch.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),i(p,{class:"mt-30",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:Z},null,8,["text"]),i(w,{show:B.value,loading:R.value,"close-on-click-overlay":"",ref:"uPickerRef","key-name":"name",columns:q.value,cancelText:e.$t("common.text.cancel"),confirmText:e.$t("common.text.confirm"),onCancel:a[6]||(a[6]=e=>B.value=!1),onConfirm:L,onChange:D,onClose:a[7]||(a[7]=e=>B.value=!1)},null,8,["show","loading","columns","cancelText","confirmText"]),i(w,{show:U.value,loading:R.value,"close-on-click-overlay":"",ref:"uPickerRef","key-name":"name",columns:$.value,cancelText:e.$t("common.text.cancel"),confirmText:e.$t("common.text.confirm"),onCancel:a[8]||(a[8]=e=>U.value=!1),onConfirm:Y,onClose:a[9]||(a[9]=e=>U.value=!1)},null,8,["show","loading","columns","cancelText","confirmText"])])),_:1})],64)}}}),[["__scopeId","data-v-bb57a8b8"]]);export{_ as default};