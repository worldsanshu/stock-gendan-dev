import{_ as l}from"./uni-nav-bar.Cpp9y_qD.js";import{V as t,U as e,x as a,y as s,z as u,A as n,C as i,H as o,Y as c,Z as r,B as f,I as d,J as m,O as _}from"./index-DHIuICwK.js";import{r as p}from"./uni-app.es.d3PbdijJ.js";import{_ as x}from"./u-icon.CwZ5kuOL.js";import{_ as b}from"./u-button.IdAWSn9u.js";import{_ as j}from"./u-picker.CFI9mqIK.js";import{_ as v}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.COKLvYFy.js";import"./uni-status-bar.WgFv0Cjl.js";import"./u-loading-icon.D2nqDR-a.js";import"./u-popup.C5uPDmGO.js";import"./u-transition.EyNGNlXc.js";import"./u-status-bar.BASBgPGq.js";const h=v({__name:"user-center-apply-for-extension",setup(v){const h=t(!1),g=e([[{label:"请选择操盘期限",index:0}]]),k=t("请选择续期期限"),C=t(0);(()=>{for(let l=1;l<=30;l++)g[0].push({label:`${l}天`,index:l})})();const T=()=>{h.value=!0},w=()=>{h.value=!1},$=l=>{k.value=l.value[0].label,C.value=l.value[0].index,w()};return(t,e)=>{const v=p(a("uni-nav-bar"),l),y=i,z=_,I=p(a("up-icon"),x),L=p(a("up-button"),b),N=p(a("up-picker"),j);return s(),u(y,null,{default:n((()=>[o(v,{title:"申请延期","status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",onClickLeft:c(r)},null,8,["onClickLeft"]),o(y,{class:"page-bg"}),o(y,{class:"main-content"},{default:n((()=>[o(y,{class:"select-box",onClick:T},{default:n((()=>[o(z,{class:f({"no-select-text":0===C.value})},{default:n((()=>[d(m(k.value),1)])),_:1},8,["class"]),o(I,{name:"arrow-down-fill",color:"#c2c2c2",size:"12"})])),_:1}),o(y,{class:"main-list"},{default:n((()=>[o(y,{class:"list-item"},{default:n((()=>[o(z,{class:"list-item-left"},{default:n((()=>[d("操盘期限")])),_:1}),o(y,{class:"list-item-right"},{default:n((()=>[o(z,null,{default:n((()=>[d("2024-07-09至2024-07-10")])),_:1})])),_:1})])),_:1}),o(y,{class:"list-item"},{default:n((()=>[o(z,{class:"list-item-left"},{default:n((()=>[d("续期产生利息")])),_:1}),o(y,{class:"list-item-right"},{default:n((()=>[o(z)])),_:1})])),_:1}),o(y,{class:"list-item"},{default:n((()=>[o(z,{class:"list-item-left"},{default:n((()=>[d("账户余额")])),_:1}),o(y,{class:"list-item-right"},{default:n((()=>[o(z,null,{default:n((()=>[d("2024-07-09至2024-07-10")])),_:1})])),_:1})])),_:1})])),_:1}),o(L,{color:"#ff4500",text:"提交续期申请"}),o(y,{class:"record-box"},{default:n((()=>[o(y,{class:"record-header"},{default:n((()=>[o(z,null,{default:n((()=>[d("申请延期记录")])),_:1}),o(I,{name:"reload",color:"#252525",size:"20"})])),_:1}),o(y,{class:"record-content-header"},{default:n((()=>[o(z,null,{default:n((()=>[d("延期期限")])),_:1}),o(z,null,{default:n((()=>[d("延期总利息")])),_:1}),o(z,null,{default:n((()=>[d("申请时间")])),_:1}),o(z,null,{default:n((()=>[d("申请状态")])),_:1})])),_:1}),o(y,{class:"record-list-content"},{default:n((()=>[o(y,{class:"list-main-content"},{default:n((()=>[o(y,{class:"list-item"},{default:n((()=>[o(y,{class:"list-item-inner"},{default:n((()=>[o(z,null,{default:n((()=>[d("中国石化")])),_:1}),o(z,null,{default:n((()=>[d("6.44")])),_:1})])),_:1}),o(y,{class:"list-item-inner"},{default:n((()=>[o(z,null,{default:n((()=>[d("100.00")])),_:1}),o(z,null,{default:n((()=>[d("6.44")])),_:1})])),_:1}),o(y,{class:"list-item-inner"},{default:n((()=>[o(z,null,{default:n((()=>[d("2024-07-10")])),_:1}),o(z,null,{default:n((()=>[d("14:15:48")])),_:1})])),_:1}),o(y,{class:"list-item-inner"},{default:n((()=>[o(z,null,{default:n((()=>[d("2024-07-10")])),_:1}),o(z,null,{default:n((()=>[d("14:15:48")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),o(N,{show:h.value,cancelText:t.$t("common.text.cancel"),confirmText:t.$t("common.text.confirm"),onCancel:w,onConfirm:$,columns:g,keyName:"label"},null,8,["show","cancelText","confirmText","columns"])])),_:1})}}},[["__scopeId","data-v-b3a7584b"]]);export{h as default};
