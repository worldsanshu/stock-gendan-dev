import{V as t,aj as a,x as e,D as s,H as o,Y as n,z as i,y as r,G as l,F as u,cc as c,C as p,Z as m}from"./index-V1zTiwCe.js";import{_ as f}from"./uni-nav-bar.B9quvnCD.js";import{c as d,r as g}from"./uni-app.es.CQrvLY-P.js";import{_ as v}from"./fc-article-list.CWzkio-l.js";import{_}from"./u-skeleton.BzTNLJVy.js";import{_ as j}from"./u-empty.CAIIj3fX.js";import{_ as x}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DZWFI7QG.js";import"./uni-status-bar.BjedvyN3.js";import"./u-icon.EexHLkhm.js";const b=x({__name:"user-center-station-message-notice",setup(x){const b=t([]);let h=t(!1);async function k(){const{data:t}=await c({page:1,pagesize:8});t?(b.value=t,h.value=!1):(b.value=[],h.value=!0)}return d((async()=>{await k(),a()})),k(),(t,a)=>{const c=g(e("uni-nav-bar"),f),d=g(e("fc-article-list"),v),x=g(e("up-skeleton"),_),k=g(e("up-empty"),j);return p(),s(u,null,[o(c,{title:t.$t("page.title.announcement"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:n(m)},null,8,["title","onClickLeft"]),o(x,{rows:"6","rows-height":"80",title:!1,isArticle:!1,loading:!n(h)&&!b.value.length},{default:i((()=>[o(d,{list:b.value},null,8,["list"])])),_:1},8,["loading"]),n(h)&&!b.value.length?(p(),r(k,{key:0,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png",text:t.$t("common.text.noData")},null,8,["text"])):l("",!0)],64)}}},[["__scopeId","data-v-b3f490f0"]]);export{b as default};
