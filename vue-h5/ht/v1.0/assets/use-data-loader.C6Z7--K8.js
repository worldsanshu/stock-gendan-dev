import{V as a}from"./index-Br34eEhS.js";function e(e,o={},r=""){const t=a(1),n=a(!1),l=a("loadmore"),u=a(!0),v=a([]);let i={};async function c(a={}){l.value="loading";const{data:n=[],error:v}=await e({...o,...a,...i,page:t.value});u.value=!1;return{data:r?function(a,e,o){const r=e.split(".");let t=a;for(const n of r)if(t=t?t[n]:void 0,void 0===t)return o;return t}(n,r,[]):n,error:v}}return{list:v,pageNum:t,noData:n,loadStatus:l,loading:u,loadData:c,loadMore:async function(){if("nomore"===l.value)return;t.value++;const{data:a,error:e}=await c();e?l.value="error":a.length?(l.value="loadmore",v.value=v.value.concat(a)):l.value="nomore"},refresh:async function(a={}){t.value=1,i=a;const{data:e,error:o}=await c();o?l.value="error":(v.value=e,v.value.length?(n.value=!1,l.value=v.value.length<10?"nomore":"loadmore"):n.value=!0)}}}export{e as u};
