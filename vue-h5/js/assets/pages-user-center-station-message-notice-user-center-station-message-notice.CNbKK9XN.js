import{V as t,ai as a,x as e,y as s,D as o,H as n,Y as i,A as r,z as l,G as u,F as p,c8 as c,Z as m}from"./index-WgdLfFQD.js";import{_ as f}from"./uni-nav-bar.hwVZAVTk.js";import{o as d,r as g}from"./uni-app.es.43u9pRjR.js";import{_}from"./fc-article-list.JE_P99b7.js";import{_ as v}from"./u-skeleton.CE9kYZKW.js";import{_ as j}from"./u-empty.jl6xQuQk.js";import{_ as x}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CX-gASbb.js";import"./uni-status-bar.BjJ5SJc3.js";import"./u-icon.COePln8_.js";const b=x({__name:"user-center-station-message-notice",setup(x){const b=t([]);let h=t(!1);async function k(){const{data:t}=await c({page:1,pagesize:8});t?(b.value=t,h.value=!1):(b.value=[],h.value=!0)}return d((async()=>{await k(),a()})),k(),(t,a)=>{const c=g(e("uni-nav-bar"),f),d=g(e("fc-article-list"),_),x=g(e("up-skeleton"),v),k=g(e("up-empty"),j);return s(),o(p,null,[n(c,{title:t.$t("page.title.announcement"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:i(m)},null,8,["title","onClickLeft"]),n(x,{rows:"6","rows-height":"80",title:!1,isArticle:!1,loading:!i(h)&&!b.value.length},{default:r((()=>[n(d,{list:b.value},null,8,["list"])])),_:1},8,["loading"]),i(h)&&!b.value.length?(s(),l(k,{key:0,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png",text:t.$t("common.text.noData")},null,8,["text"])):u("",!0)],64)}}},[["__scopeId","data-v-b3f490f0"]]);export{b as default};
