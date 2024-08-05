import{R as e,V as a,U as t,x as l,y as o,D as s,H as n,Y as r,A as i,F as d,q as u,cd as c,C as m,Z as p,z as f,G as b,bO as _}from"./index-BSZMSohE.js";import{_ as g}from"./uni-nav-bar.DaV7kknO.js";import{r as k}from"./uni-app.es.xTxZrAO-.js";import{_ as v}from"./u-input.Cv139uxi.js";import{_ as x,a as y}from"./u-form.BetUNbkv.js";import{_ as h}from"./u-upload.CgISlsns.js";import{_ as w}from"./uni-section.DL9TJJdr.js";import{_ as j}from"./u-button.Bc9J1urq.js";import{_ as V}from"./u-picker.0OeSiJSu.js";import{d as T,k as C}from"./wallet.BMFo6IIZ.js";import{_ as q}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.UvJfiB1T.js";import"./uni-status-bar.DM8Vigxm.js";import"./u-icon.rSo6jUIp.js";import"./u-line.Bg8FUxRG.js";import"./u-loading-icon.oQN9K4Zk.js";import"./u-popup.BrdwHkLw.js";import"./u-transition.DezxqfD0.js";import"./u-status-bar.C01COvFq.js";const $=q(Object.assign({name:"user-center-wallet-management-add-address"},{__name:"user-center-wallet-management-add-address",setup(q){const{t:$}=e(),L=a(!1),A=a([[]]),F=a(),U=a([]),R=a([]),P=a([]),O=a([[]]),D=a(!1),S=a(!1),W=t({id:"",idText:"",alias:"",address:"",payment_code:"",bank_text:"",bank_code:"",bank_owner:"",bank_number:""}),Y=a(""),Z={idText:{type:"string",required:!0,message:"请选择钱包/币种",trigger:["blur","change"]},alias:{type:"string",required:!0,message:$("addAddress.text.tip2"),trigger:["blur","change"]},address:{type:"string",required:!0,message:$("addAddress.text.tip3"),trigger:["blur","change"]},bank_code:{type:"string",required:!0,message:"请选择开户银行",trigger:["blur","change"]},bank_owner:{type:"string",required:!0,message:"请输入开户姓名",trigger:["blur","change"]},bank_number:{type:"string",required:!0,message:"请输入卡号",trigger:["blur","change"]}};async function z(){if("code"!==Y.value||""!==W.address||""!==W.payment_code)if("address"!==Y.value||""!==W.address)if("bank"!==Y.value||""!==W.bank_number)try{await F.value.validate();const{error:e,message:a}=await C(W);await u({title:a,icon:e?"none":"success",duration:1e3}),e||setTimeout(p,1e3)}catch(e){e.length&&await u({title:e[0].message,icon:"none"})}else u({title:"请输入卡号!",icon:"none"});else u({title:"请输入收款地址!",icon:"none"});else u({title:"请输入收款地址或上传收款码!",icon:"none"})}async function B(e){W.id=e.value[0].id,W.idText=e.value[0].name,Y.value=e.value[0].WithdrawalTypeSelect,L.value=!1}function G(e){W.bank_text=e.value[0].name,W.bank_code=e.value[0].id,D.value=!1}return async function(){const{error:e,message:a,data:t}=await T({type:"wallet"});e?await u({title:a,icon:"none"}):A.value=[t.list]}(),async function(){const{error:e,message:a,data:t}=await c();e?await u({title:a,icon:"none"}):O.value=[t.bank]}(),(e,a)=>{const t=k(l("uni-nav-bar"),g),c=k(l("up-input"),v),T=k(l("up-form-item"),x),C=k(l("up-form"),y),q=k(l("up-upload"),h),H=m,I=k(l("uni-section"),w),N=k(l("up-button"),j),X=k(l("up-picker"),V);return o(),s(d,null,[n(t,{title:e.$t("page.title.addAddress"),border:!1,"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",fixed:"",onClickLeft:r(p)},null,8,["title","onClickLeft"]),n(H,{class:"body"},{default:i((()=>[n(C,{model:W,rules:Z,"border-bottom":"",class:"form","label-width":"120",labelPosition:"top",ref_key:"formRef",ref:F},{default:i((()=>[n(T,{required:"",label:"钱包/币种",prop:"idText"},{default:i((()=>[n(c,{placeholder:Z.idText.message,modelValue:W.idText,"onUpdate:modelValue":a[0]||(a[0]=e=>W.idText=e),type:"text","disabled-color":"#fff","placeholder-style":"color: '#333'","suffix-icon":"arrow-down",onClick:a[1]||(a[1]=e=>L.value=!0)},null,8,["placeholder","modelValue"])])),_:1}),"address"===Y.value||"code"===Y.value?(o(),f(T,{key:0,required:"",label:e.$t("addAddress.text.aliases"),prop:"alias"},{default:i((()=>[n(c,{placeholder:Z.alias.message,modelValue:W.alias,"onUpdate:modelValue":a[2]||(a[2]=e=>W.alias=e),type:"text"},null,8,["placeholder","modelValue"])])),_:1},8,["label"])):b("",!0),"address"===Y.value||"code"===Y.value?(o(),f(T,{key:1,label:e.$t("addAddress.text.address"),required:"","label-width":"100%"},{default:i((()=>[n(c,{placeholder:Z.address.message,modelValue:W.address,"onUpdate:modelValue":a[3]||(a[3]=e=>W.address=e),type:"text"},null,8,["placeholder","modelValue"])])),_:1},8,["label"])):b("",!0),"bank"===Y.value?(o(),f(T,{key:2,required:"",label:e.$t("addBankCard.text.bankAccount"),prop:"bank_code"},{default:i((()=>[n(c,{type:"text",modelValue:W.bank_text,"onUpdate:modelValue":a[4]||(a[4]=e=>W.bank_text=e),placeholder:Z.bank_code.message,"disabled-color":"#fff","placeholder-style":"color: '#333'","suffix-icon":"arrow-down",onClick:a[5]||(a[5]=e=>D.value=!0)},null,8,["modelValue","placeholder"])])),_:1},8,["label"])):b("",!0),"bank"===Y.value?(o(),f(T,{key:3,required:"",label:"姓名",prop:"bank_owner"},{default:i((()=>[n(c,{placeholder:Z.bank_owner.message,modelValue:W.bank_owner,"onUpdate:modelValue":a[6]||(a[6]=e=>W.bank_owner=e),type:"text"},null,8,["placeholder","modelValue"])])),_:1})):b("",!0),"bank"===Y.value?(o(),f(T,{key:4,label:"卡号",required:"","label-width":"100%",prop:"bank_number"},{default:i((()=>[n(c,{placeholder:Z.bank_number.message,modelValue:W.bank_number,"onUpdate:modelValue":a[7]||(a[7]=e=>W.bank_number=e),type:"text"},null,8,["placeholder","modelValue"])])),_:1})):b("",!0)])),_:1},8,["model"]),"code"===Y.value?(o(),f(I,{key:0,title:"请上传收款码",type:"line",padding:"0 30rpx"},{default:i((()=>[n(H,{class:"passport-wrapper"},{default:i((()=>[n(q,{maxCount:1,fileList:U.value,onAfterRead:a[8]||(a[8]=e=>async function(e,a){const t="passportFileList"===a?U:"frontFileList"===a?R:P,l="passportFileList"===a?"passport_pic":"frontFileList"===a?"card_pic_hand":"card_pic_back";t.value=[{...e.file,status:"uploading",message:$("verified.text.uploading")}];const o=await _(e.file.url);W.payment_code=o.data.file_path,1===Number(o.status)?(t.value=[{...e.file,status:"success",message:$("verified.text.uploadSuccessFully")}],W.value[l]=o.data.file_path):await u({title:o.message||$("verified.text.uploadFailed"),icon:"none"})}(e,"passportFileList")),onDelete:a[9]||(a[9]=e=>(U.value=[],W.payment_code="")),class:"passport-content mt-50",name:"url"},null,8,["fileList"])])),_:1})])),_:1})):b("",!0),n(N,{text:e.$t("common.text.submit"),class:"mt-30",color:"rgb(255, 69, 0)",onClick:z},null,8,["text"]),n(X,{ref:"uPickerRef",show:L.value,columns:A.value,cancelText:e.$t("common.text.cancel"),confirmText:e.$t("common.text.confirm"),onCancel:a[10]||(a[10]=e=>L.value=!1),onConfirm:B,onClose:a[11]||(a[11]=e=>L.value=!1),"close-on-click-overlay":"","key-name":"name"},null,8,["show","columns","cancelText","confirmText"]),n(X,{show:D.value,loading:S.value,"close-on-click-overlay":"",ref:"uPickerRef","key-name":"name",columns:O.value,cancelText:e.$t("common.text.cancel"),confirmText:e.$t("common.text.confirm"),onCancel:a[12]||(a[12]=e=>D.value=!1),onConfirm:G,onClose:a[13]||(a[13]=e=>D.value=!1)},null,8,["show","loading","columns","cancelText","confirmText"])])),_:1})],64)}}}),[["__scopeId","data-v-e5b5da41"]]);export{$ as default};
