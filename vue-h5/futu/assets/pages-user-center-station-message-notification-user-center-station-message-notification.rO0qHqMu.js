import{V as t,ah as a,x as s,y as i,D as e,H as n,Y as o,A as l,F as r,ca as c,Z as u,E as f,C as d,z as _,I as m,J as p,P as g}from"./index-BpNRNyt1.js";import{_ as j}from"./uni-nav-bar.DzQ3GIPc.js";import{o as v,r as b}from"./uni-app.es.DOVXrixg.js";import{_ as k}from"./u-skeleton.C1ZdLD0X.js";import{_ as h}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.BdLyHM4-.js";import"./uni-status-bar.BKUrsIDI.js";const x=h({__name:"user-center-station-message-notification",setup(h){const x=t([]);async function y(){const{data:t}=await c({page:1});x.value=t.data}return v((async()=>{await y(),a()})),y(),(t,a)=>{const c=b(s("uni-nav-bar"),j),v=d,h=g,y=b(s("up-skeleton"),k);return i(),e(r,null,[n(c,{title:t.$t("page.title.notify"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:o(u)},null,8,["title","onClickLeft"]),n(y,{rows:"6","rows-height":"80",title:!1,isArticle:!1,loading:!x.value.length},{default:l((()=>[n(v,{class:"notification-list"},{default:l((()=>[(i(!0),e(r,null,f(x.value,(t=>(i(),_(v,{class:"notification-item",key:t.id},{default:l((()=>[n(v,{class:"notification-item__title"},{default:l((()=>[m(p(t.title),1)])),_:2},1024),n(h,{class:"notification-item__desc",nodes:t.info},null,8,["nodes"]),n(v,{class:"notification-item__date"},{default:l((()=>[m(p(t.create_time),1)])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1},8,["loading"])],64)}}},[["__scopeId","data-v-d859e695"]]);export{x as default};