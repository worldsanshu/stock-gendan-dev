import{V as a,ah as t,x as e,y as s,D as r,H as l,Y as o,z as i,G as u,A as n,F as d,ap as p,C as m,I as c,N as _,O as f}from"./index-BnwyELQR.js";import{_ as v}from"./uni-nav-bar.Bi8ytwCv.js";import{o as j,a as g,r as y}from"./uni-app.es.dFrsx9lz.js";import{_ as h}from"./u-tabs.pxpLvgTd.js";import{_ as k}from"./u-search.C7ZsJkCl.js";import{u as b,_ as x}from"./use-star-investor.Cs5Sdfak.js";import{_ as C}from"./u-skeleton.BqMinqrz.js";import{_ as w}from"./u-empty.mK4L7rtk.js";import{_ as V}from"./u-loadmore.tQ_p4Csg.js";import{a as O,O as U}from"./index.CFYQMZ7v.js";import{_ as A}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CUdzfWEk.js";import"./uni-status-bar.dwNjomZi.js";import"./u-icon.D5CTgztk.js";import"./u-avatar.iO3ADwhJ.js";import"./u-text.dn-N2krA.js";import"./u-link.BuUar1_8.js";import"./u-tag.6COmr6uH.js";import"./u-transition.HggbuTuJ.js";import"./use-data-loader.CKW3iAtK.js";import"./u-line.CycUIw9s.js";import"./u-loading-icon.DDFfdENh.js";const D=""+new URL("star-investor-bg-CkdrfHE4.png",import.meta.url).href,E=A({__name:"star-investor-v2",setup(A){let E=0;const F=a(""),H=a(""),{tabs:I,tabCurrent:N,list:R,loading:S,loadMore:z,loadStatus:B,noData:G,refresh:L}=b();function M(){p("/pages/record/record",{id:20})}return O().then((a=>{H.value=a.data.module})),j((async()=>{await L(),t()})),g(z),U({type:1,page:1}).then((a=>{0===a.data.list.data.length&&(E=1)})),L(),(a,t)=>{const p=y(e("uni-nav-bar"),v),j=y(e("up-tabs"),h),g=y(e("up-search"),k),b=m,O=y(e("fc-star-investor-list"),x),U=y(e("up-skeleton"),C),A=y(e("up-empty"),w),Q=y(e("up-loadmore"),V),T=_,W=f;return s(),r(d,null,[l(p,{title:"明星投资者","status-bar":"","background-color":"#EB5528",color:"#fff","right-text":"优投记录",fixed:"",border:!1,onClickRight:M}),H.value&&0===H.value.trader_list_type&&0===o(E)?(s(),i(j,{key:0,list:o(I),scrollable:!1,current:o(N),onChange:t[0]||(t[0]=a=>N.value=a.index)},null,8,["list","current"])):u("",!0),H.value&&1===H.value.trader_list_type?(s(),i(b,{key:1,class:"sear"},{default:n((()=>[l(g,{placeholder:"输入导师名称",modelValue:F.value,"onUpdate:modelValue":t[1]||(t[1]=a=>F.value=a),onChange:o(L),onClear:o(L)},null,8,["modelValue","onChange","onClear"])])),_:1})):u("",!0),H.value&&0===H.value.trader_list_type||F.value?(s(),i(b,{key:2,class:"body p-30"},{default:n((()=>[l(U,{loading:o(S),rows:"5",title:!1,"rows-height":140,isArticle:!1},{default:n((()=>[l(O,{list:o(R)},null,8,["list"])])),_:1},8,["loading"]),o(G)?(s(),i(A,{key:0,mode:"data",icon:"/static/empty/empty-data.png"})):u("",!0),o(G)||o(S)?u("",!0):(s(),i(Q,{key:1,status:o(B),onClick:o(z)},null,8,["status","onClick"]))])),_:1})):u("",!0),H.value&&1===H.value.trader_list_type&&!F.value?(s(),i(b,{key:3,class:"keyword-no-data"},{default:n((()=>[l(T,{src:D,mode:"widthFix",class:"img"}),l(b,{class:"title"},{default:n((()=>[c("跟买如何操作？")])),_:1}),l(W,null,{default:n((()=>[c("跟买十分简单，在搜索框中输入您想跟买的明星导师名称，")])),_:1}),l(W,null,{default:n((()=>[c("就可以查看到明星导师的详细信息，")])),_:1}),l(W,null,{default:n((()=>[c("点击头像就可以进入申请页面操作。")])),_:1}),l(W,{style:{"margin-top":"50rpx"}},{default:n((()=>[c("只有在您每天确认跟买后，操盘师才会去操盘您的跟买资金。")])),_:1}),l(W,{class:"red"},{default:n((()=>[c("请注意，若您确认跟买的时间太迟，有可能买不上当日的金股。")])),_:1})])),_:1})):u("",!0)],64)}}},[["__scopeId","data-v-4059e80a"]]);export{E as default};
