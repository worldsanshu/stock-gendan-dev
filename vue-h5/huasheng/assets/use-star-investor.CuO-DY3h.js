import{x as e,y as t,z as a,A as s,D as l,F as i,E as n,H as r,I as o,J as m,G as p,aq as c,C as u,O as d,V as y,ae as _}from"./index-BrsO_Ji3.js";import{_ as f}from"./u-avatar.YmgTHMVD.js";import{r as k}from"./uni-app.es.ouLKK4z2.js";import{_ as x}from"./u-tag.B7VwVirw.js";import{_ as g}from"./_plugin-vue_export-helper.BCo6x5W8.js";import{u as v}from"./use-data-loader.C6cbiAmX.js";import{O as C}from"./index.Bm1oaBar.js";const b=g({__name:"fc-star-investor-list",props:{list:Array},setup(y){function _(e){2===e.type?c("/pages/vip-merchandiser/vip-merchandiser",{id:e.id,title:"一键优投"}):c("/pages/daily-documentary/daily-documentary",{id:e.id,title:"每日优投"})}return(c,g)=>{const v=u,C=d,b=k(e("up-avatar"),f),h=k(e("up-tag"),x);return t(),a(v,{class:"list"},{default:s((()=>[(t(!0),l(i,null,n(y.list,(e=>(t(),a(v,{class:"list-item p-30 mb-30",key:e.id,onClick:t=>_(e)},{default:s((()=>[e.product_name?(t(),a(v,{key:0,class:"title-wrapper"},{default:s((()=>[r(v,{class:"mark"}),r(C,null,{default:s((()=>[o("产品名："+m(e.product_name),1)])),_:2},1024)])),_:2},1024)):p("",!0),r(v,{class:"list-item-body"},{default:s((()=>[r(v,{class:"list-item-avatar"},{default:s((()=>[r(b,{src:e.head_img,size:"126rpx"},null,8,["src"])])),_:2},1024),r(v,{class:"list-item-content ml-20"},{default:s((()=>[r(v,{class:"font-16-bold"},{default:s((()=>[o("明星导师: "+m(e.name),1)])),_:2},1024),e.level_name||e.experience?(t(),a(v,{key:0,class:"tag-wrapper"},{default:s((()=>[e.level_name?(t(),a(v,{key:0,class:"tag-item tag-item-red"},{default:s((()=>[o(m(e.level_name),1)])),_:2},1024)):p("",!0),e.experience?(t(),a(v,{key:1,class:"tag-item tag-item-blue"},{default:s((()=>[o(m(e.experience),1)])),_:2},1024)):p("",!0)])),_:2},1024)):p("",!0),e.explain?(t(),a(v,{key:1,class:"font-12-grey mt-10"},{default:s((()=>[o(m(e.explain),1)])),_:2},1024)):p("",!0)])),_:2},1024)])),_:2},1024),r(v,{class:"list-item-bottom mt-30"},{default:s((()=>[e.min_money?(t(),a(h,{key:0,text:`额度：${e.min_money}-${e.max_money}`,size:"mini",type:"error",class:"ml-10",onClick:t=>_(e)},null,8,["text","onClick"])):p("",!0),r(h,{text:`导师抽佣：${e.divide_into}`,size:"mini",type:"error",class:"ml-10",onClick:t=>_(e)},null,8,["text","onClick"]),e.system_commission?(t(),a(h,{key:1,text:`平台抽成：${e.system_commission}`,size:"mini",type:"error",class:"ml-10",onClick:t=>_(e)},null,8,["text","onClick"])):p("",!0)])),_:2},1024),2===e.type?(t(),a(v,{key:1,style:{position:"absolute",top:"20rpx",right:"20rpx"}},{default:s((()=>[r(h,{onClick:t=>_(e),text:"一键优投",plain:"",size:"mini",type:"error"},null,8,["onClick"])])),_:2},1024)):p("",!0)])),_:2},1032,["onClick"])))),128))])),_:1})}}},[["__scopeId","data-v-e0cb2080"]]);function h(e=0){const t=[{name:"全部",type:0},{name:"每日优投",type:1},{name:"一键优投",type:2}],a=y(e),{list:s,refresh:l,loadMore:i,loadStatus:n,loading:r,noData:o}=v(C,null,"list.data");async function m(e){await l({type:t[a.value].type,keyword:e})}return _(a,(async()=>{await m()})),{tabs:t,tabCurrent:a,list:s,refresh:m,loadMore:i,loadStatus:n,loading:r,noData:o}}export{b as _,h as u};
