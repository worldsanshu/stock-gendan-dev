import{V as a,ai as t,x as e,y as s,D as r,H as l,Y as o,z as i,G as u,A as n,F as d,aq as p,C as m,I as c,N as _,O as f}from"./index-BrsO_Ji3.js";import{_ as v}from"./uni-nav-bar.8_lQtta2.js";import{o as j,a as g,r as y}from"./uni-app.es.ouLKK4z2.js";import{_ as h}from"./u-tabs.Dx4IVA90.js";import{_ as k}from"./u-search.YGLSE4lv.js";import{u as b,_ as x}from"./use-star-investor.CuO-DY3h.js";import{_ as C}from"./u-skeleton.XzFG9z_T.js";import{_ as w}from"./u-empty.CcPyWyjQ.js";import{_ as V}from"./u-loadmore.ZTEpmFrJ.js";import{a as O,O as U}from"./index.Bm1oaBar.js";import{_ as A}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CvdRkdm4.js";import"./uni-status-bar.CL5lYheB.js";import"./u-icon.D7SiMdEc.js";import"./u-avatar.YmgTHMVD.js";import"./u-text.DyhFkNDM.js";import"./u-link.Bt76wKhj.js";import"./u-tag.B7VwVirw.js";import"./u-transition.B56uRZMS.js";import"./use-data-loader.C6cbiAmX.js";import"./u-line.CQM3Ema6.js";import"./u-loading-icon.CJYNNQXZ.js";const D=""+new URL("star-investor-bg-CkdrfHE4.png",import.meta.url).href,E=A({__name:"star-investor-v2",setup(A){let E=0;const F=a(""),H=a(""),{tabs:I,tabCurrent:N,list:R,loading:S,loadMore:q,loadStatus:z,noData:B,refresh:G}=b();function L(){p("/pages/record/record",{id:20})}return O().then((a=>{H.value=a.data.module})),j((async()=>{await G(),t()})),g(q),U({type:1,page:1}).then((a=>{0===a.data.list.data.length&&(E=1)})),G(),(a,t)=>{const p=y(e("uni-nav-bar"),v),j=y(e("up-tabs"),h),g=y(e("up-search"),k),b=m,O=y(e("fc-star-investor-list"),x),U=y(e("up-skeleton"),C),A=y(e("up-empty"),w),M=y(e("up-loadmore"),V),Q=_,T=f;return s(),r(d,null,[l(p,{title:"明星投资者","status-bar":"","background-color":"#EB5528",color:"#fff","right-text":"优投记录",fixed:"",border:!1,onClickRight:L}),H.value&&0===H.value.trader_list_type&&0===o(E)?(s(),i(j,{key:0,list:o(I),scrollable:!1,current:o(N),onChange:t[0]||(t[0]=a=>N.value=a.index)},null,8,["list","current"])):u("",!0),H.value&&1===H.value.trader_list_type?(s(),i(b,{key:1,class:"sear"},{default:n((()=>[l(g,{placeholder:"输入导师名称",modelValue:F.value,"onUpdate:modelValue":t[1]||(t[1]=a=>F.value=a),onChange:o(G),onClear:o(G)},null,8,["modelValue","onChange","onClear"])])),_:1})):u("",!0),H.value&&0===H.value.trader_list_type||F.value?(s(),i(b,{key:2,class:"body p-30"},{default:n((()=>[l(U,{loading:o(S),rows:"5",title:!1,"rows-height":140,isArticle:!1},{default:n((()=>[l(O,{list:o(R)},null,8,["list"])])),_:1},8,["loading"]),o(B)?(s(),i(A,{key:0,mode:"data",icon:"/static/empty/empty-data.png"})):u("",!0),o(B)||o(S)?u("",!0):(s(),i(M,{key:1,status:o(z),onClick:o(q)},null,8,["status","onClick"]))])),_:1})):u("",!0),H.value&&1===H.value.trader_list_type&&!F.value?(s(),i(b,{key:3,class:"keyword-no-data"},{default:n((()=>[l(Q,{src:D,mode:"widthFix",class:"img"}),l(b,{class:"title"},{default:n((()=>[c("跟买如何操作？")])),_:1}),l(T,null,{default:n((()=>[c("跟买十分简单，在搜索框中输入您想跟买的明星导师名称，")])),_:1}),l(T,null,{default:n((()=>[c("就可以查看到明星导师的详细信息，")])),_:1}),l(T,null,{default:n((()=>[c("点击头像就可以进入申请页面操作。")])),_:1}),l(T,{style:{"margin-top":"50rpx"}},{default:n((()=>[c("只有在您每天确认跟买后，操盘师才会去操盘您的跟买资金。")])),_:1}),l(T,{class:"red"},{default:n((()=>[c("请注意，若您确认跟买的时间太迟，有可能买不上当日的金股。")])),_:1})])),_:1})):u("",!0)],64)}}},[["__scopeId","data-v-4059e80a"]]);export{E as default};
