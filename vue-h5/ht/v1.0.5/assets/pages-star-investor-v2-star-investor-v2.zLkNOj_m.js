import{V as a,aj as t,x as e,D as s,H as r,Y as l,y as o,G as i,z as u,F as n,B as d,C as p,I as m,aq as c,N as _,O as f}from"./index-hU8OKPmH.js";import{_ as v}from"./uni-nav-bar.U3Cmk77Z.js";import{c as j,d as g,r as y}from"./uni-app.es.DADla7ur.js";import{_ as h}from"./u-tabs.DteEnMcl.js";import{_ as k}from"./u-search.CuIz3362.js";import{u as b,_ as C}from"./use-star-investor.CRlA3lzs.js";import{_ as x}from"./u-skeleton.BLpUwz8I.js";import{_ as w}from"./u-empty.BsWToqDu.js";import{_ as V}from"./u-loadmore.DqssgiGm.js";import{a as B,O as D}from"./index.DUzjlh-0.js";import{_ as E}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DgCYXD5d.js";import"./uni-status-bar.BC4SStPU.js";import"./u-icon.BX8bEo6J.js";import"./u-avatar.CL9_xXf2.js";import"./u-text.BOVY5aNF.js";import"./u-link.BdNvV42H.js";import"./u-tag.DQbIOaQN.js";import"./u-transition.hFcAyfbx.js";import"./use-data-loader.DF5KNs82.js";import"./u-line.ILQrRGaQ.js";import"./u-loading-icon.DGLmxP69.js";const H=""+new URL("star-investor-bg-CkdrfHE4.png",import.meta.url).href,I=E({__name:"star-investor-v2",setup(E){let I=0;const U=a(""),A=a(""),{tabs:F,tabCurrent:N,list:O,loading:R,loadMore:S,loadStatus:q,noData:z,refresh:G}=b();function J(){c("/pages/record/record",{id:20})}return B().then((a=>{A.value=a.data.module})),j((async()=>{await G(),t()})),g(S),D({type:1,page:1}).then((a=>{0===a.data.list.data.length&&(I=1)})),G(),(a,t)=>{const c=y(e("uni-nav-bar"),v),j=y(e("up-tabs"),h),g=y(e("up-search"),k),b=d,B=y(e("fc-star-investor-list"),C),D=y(e("up-skeleton"),x),E=y(e("up-empty"),w),K=y(e("up-loadmore"),V),L=_,M=f;return p(),s(n,null,[r(c,{title:"明星投资者","status-bar":"","background-color":"#EB5528",color:"#fff","right-text":"优投记录",fixed:"",border:!1,onClickRight:J}),A.value&&0===A.value.trader_list_type&&0===l(I)?(p(),o(j,{key:0,list:l(F),scrollable:!1,current:l(N),onChange:t[0]||(t[0]=a=>N.value=a.index)},null,8,["list","current"])):i("",!0),A.value&&1===A.value.trader_list_type?(p(),o(b,{key:1,class:"sear"},{default:u((()=>[r(g,{placeholder:"输入导师名称",modelValue:U.value,"onUpdate:modelValue":t[1]||(t[1]=a=>U.value=a),onChange:l(G),onClear:l(G)},null,8,["modelValue","onChange","onClear"])])),_:1})):i("",!0),A.value&&0===A.value.trader_list_type||U.value?(p(),o(b,{key:2,class:"body p-30"},{default:u((()=>[r(D,{loading:l(R),rows:"5",title:!1,"rows-height":140,isArticle:!1},{default:u((()=>[r(B,{list:l(O)},null,8,["list"])])),_:1},8,["loading"]),l(z)?(p(),o(E,{key:0,mode:"data",icon:"/static/empty/empty-data.png"})):i("",!0),l(z)||l(R)?i("",!0):(p(),o(K,{key:1,status:l(q),onClick:l(S)},null,8,["status","onClick"]))])),_:1})):i("",!0),A.value&&1===A.value.trader_list_type&&!U.value?(p(),o(b,{key:3,class:"keyword-no-data"},{default:u((()=>[r(L,{src:H,mode:"widthFix",class:"img"}),r(b,{class:"title"},{default:u((()=>[m("跟买如何操作？")])),_:1}),r(M,null,{default:u((()=>[m("跟买十分简单，在搜索框中输入您想跟买的明星导师名称，")])),_:1}),r(M,null,{default:u((()=>[m("就可以查看到明星导师的详细信息，")])),_:1}),r(M,null,{default:u((()=>[m("点击头像就可以进入申请页面操作。")])),_:1}),r(M,{style:{"margin-top":"50rpx"}},{default:u((()=>[m("只有在您每天确认跟买后，操盘师才会去操盘您的跟买资金。")])),_:1}),r(M,{class:"red"},{default:u((()=>[m("请注意，若您确认跟买的时间太迟，有可能买不上当日的金股。")])),_:1})])),_:1})):i("",!0)],64)}}},[["__scopeId","data-v-4059e80a"]]);export{I as default};