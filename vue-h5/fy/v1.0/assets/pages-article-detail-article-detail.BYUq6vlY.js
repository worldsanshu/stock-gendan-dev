import{_ as a}from"./uni-nav-bar.CxJfmD0V.js";import{R as t,V as e,cj as s,aK as o,x as i,y as n,D as r,H as l,Y as u,A as p,F as c,C as d,Z as m,z as f,G as _,P as g}from"./index-v5N4FCa5.js";import{r as v}from"./uni-app.es.BBb7LdIV.js";import{_ as j}from"./u-empty.C3tH2qBO.js";import{_ as b}from"./u-skeleton.PcpnXMSs.js";import{_ as y}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DUeGXWcA.js";import"./uni-status-bar.D590Ci0c.js";import"./u-icon.C6i9Eu8q.js";const k={data:()=>({}),methods:{}},x=y(Object.assign(k,{__name:"article-detail",props:{id:String,type:String},setup(y){const{t:k}=t(),x=y,F=e(""),h=e(!0),w=e(""),C="2"===x.type?s:o;return async function(){const{data:a}=await C({id:x.id});w.value=a.title||k("page.title.articleDetails"),h.value=!1,F.value=a.content}(),(t,e)=>{const s=v(i("uni-nav-bar"),a),o=g,y=v(i("up-empty"),j),k=v(i("up-skeleton"),b),x=d;return n(),r(c,null,[l(s,{title:w.value,"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:u(m)},null,8,["title","onClickLeft"]),l(x,{class:"details-page"},{default:p((()=>[l(k,{rows:"10",title:"",loading:h.value},{default:p((()=>[F.value?(n(),f(o,{key:0,class:"content",nodes:F.value},null,8,["nodes"])):_("",!0),F.value?_("",!0):(n(),f(y,{key:1,mode:"data",width:"160","margin-top":"450rpx",icon:"/static/Vertretung/noMoreData@2x.png"}))])),_:1},8,["loading"])])),_:1})],64)}}}),[["__scopeId","data-v-aaae156a"]]);export{x as default};