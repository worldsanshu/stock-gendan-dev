import{_ as a}from"./uni-nav-bar.D3FG-aeM.js";import{R as t,V as e,cm as s,aJ as o,x as i,y as n,D as r,H as l,Y as u,A as p,F as c,C as d,Z as m,z as f,G as v,P as g}from"./index-DxUBkcQO.js";import{r as _}from"./uni-app.es.WyEFFhdr.js";import{_ as j}from"./u-empty.B-uqFSHu.js";import{_ as y}from"./u-skeleton.CGJIjGk_.js";import{_ as b}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.BDom0B1X.js";import"./uni-status-bar.DTIfAn29.js";import"./u-icon.BCEdui8G.js";const k={data:()=>({}),methods:{}},x=b(Object.assign(k,{__name:"article-detail",props:{id:String,type:String},setup(b){const{t:k}=t(),x=b,w=e(""),F=e(!0),h=e(""),C="2"===x.type?s:o;return async function(){const{data:a}=await C({id:x.id});h.value=a.title||k("page.title.articleDetails"),F.value=!1,w.value=a.content}(),(t,e)=>{const s=_(i("uni-nav-bar"),a),o=g,b=_(i("up-empty"),j),k=_(i("up-skeleton"),y),x=d;return n(),r(c,null,[l(s,{title:h.value,"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:u(m)},null,8,["title","onClickLeft"]),l(x,{class:"details-page"},{default:p((()=>[l(k,{rows:"10",title:"",loading:F.value},{default:p((()=>[w.value?(n(),f(o,{key:0,class:"content",nodes:w.value},null,8,["nodes"])):v("",!0),w.value?v("",!0):(n(),f(b,{key:1,mode:"data",width:"160","margin-top":"450rpx",icon:"/static/Vertretung/noMoreData@2x.png"}))])),_:1},8,["loading"])])),_:1})],64)}}}),[["__scopeId","data-v-aaae156a"]]);export{x as default};
