import{V as t,ai as e,c6 as a,x as s,y as i,D as n,H as l,Y as o,A as r,F as u,c7 as c,C as m,Z as g,i as p,c8 as f}from"./index-MDKEdHpS.js";import{_ as b}from"./uni-nav-bar.CUk9QvF6.js";import{o as d,r as x}from"./uni-app.es.4qAlN9XI.js";import{_,a as h}from"./uni-list.BdYhZYhT.js";import"./uni-icons.DwctlgQK.js";import"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.DmuBQuz_.js";import"./uni-badge.DDajrgQS.js";const k=Object.assign({name:"user-center-station-message"},{__name:"user-center-station-message",setup(k){const j=t({}),v=t({});async function C(){const{data:t}=await c();j.value=t.ggao,v.value=t.messgae}return d((async()=>{await C(),e()})),C(),a().then(),(t,e)=>{const a=x(s("uni-nav-bar"),b),c=x(s("uni-list-item"),_),d=x(s("uni-list"),h),k=m;return i(),n(u,null,[l(a,{title:t.$t("page.title.internalMessage"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:o(g)},null,8,["title","onClickLeft"]),l(k,{class:"body"},{default:r((()=>[l(d,null,{default:r((()=>[l(c,{thumb:"/static/images/notice.png",title:t.$t("internalMessage.text.officialNews"),note:j.value.title,"right-text":j.value.create_time,"show-badge":"",clickable:"",onClick:o(p)},null,8,["title","note","right-text","onClick"]),l(c,{thumb:"/static/images/systemNotification.png",title:t.$t("internalMessage.text.systemInformation"),note:v.value.title,"right-text":v.value.create_time,"show-badge":"",clickable:"",onClick:o(f)},null,8,["title","note","right-text","onClick"])])),_:1})])),_:1})],64)}}});export{k as default};
