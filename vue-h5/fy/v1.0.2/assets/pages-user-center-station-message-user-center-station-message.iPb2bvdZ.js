import{V as t,ah as e,c6 as a,x as s,y as i,D as n,H as l,Y as o,A as r,F as u,c7 as c,C as m,Z as g,i as p,c8 as f}from"./index-BI_M_jYu.js";import{_ as b}from"./uni-nav-bar.DhAo6T7_.js";import{o as d,r as h}from"./uni-app.es.CYmSWya5.js";import{_ as x,a as _}from"./uni-list.Cxa7nLOd.js";import"./uni-icons.B8p3dwa4.js";import"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.ajwAtXvb.js";import"./uni-badge.BrERkTF2.js";const j=Object.assign({name:"user-center-station-message"},{__name:"user-center-station-message",setup(j){const k=t({}),v=t({});async function C(){const{data:t}=await c();k.value=t.ggao,v.value=t.messgae}return d((async()=>{await C(),e()})),C(),a().then(),(t,e)=>{const a=h(s("uni-nav-bar"),b),c=h(s("uni-list-item"),x),d=h(s("uni-list"),_),j=m;return i(),n(u,null,[l(a,{title:t.$t("page.title.internalMessage"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:o(g)},null,8,["title","onClickLeft"]),l(j,{class:"body"},{default:r((()=>[l(d,null,{default:r((()=>[l(c,{thumb:"/static/images/notice.png",title:t.$t("internalMessage.text.officialNews"),note:k.value.title,"right-text":k.value.create_time,"show-badge":"",clickable:"",onClick:o(p)},null,8,["title","note","right-text","onClick"]),l(c,{thumb:"/static/images/systemNotification.png",title:t.$t("internalMessage.text.systemInformation"),note:v.value.title,"right-text":v.value.create_time,"show-badge":"",clickable:"",onClick:o(f)},null,8,["title","note","right-text","onClick"])])),_:1})])),_:1})],64)}}});export{j as default};
