import{Z as t,ai as a,x as s,y as e,z as i,A as l,C as o,H as r,D as n,E as u,F as p,G as m,I as d,J as _}from"./index-CRttwwij.js";import{_ as c}from"./uni-nav-bar.QMl_o85J.js";import{r as g}from"./uni-app.es.otF8raCd.js";import{_ as f}from"./u-loadmore.CnALoBYK.js";import{_ as h}from"./u-empty.fbOuqZBq.js";import{s as j}from"./index.CgRNu-Dw.js";import{_ as b}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.Bz4Sh8gD.js";import"./uni-status-bar.B9okywpV.js";import"./u-line.C5lrB_C7.js";import"./u-loading-icon.Cvek95z1.js";import"./u-icon.CVzxT_4w.js";const k=b({data:()=>({page:1,allList:[],per_page:1,status:"loadmore"}),methods:{back:t,onReachBottom(){this.page>=this.per_page&&(this.status="nomore"),this.page>=this.per_page||(this.status="loading",this.page++,setTimeout((()=>{this.getInve()}),0))},getInve(){j({page:this.page}).then((t=>{for(let a=0;a<t.data.list.data.length;a++)this.allList.push(t.data.list.data[a]);this.per_page=t.data.list.last_page}))}},mounted(){this.getInve()},onPullDownRefresh(){this.page=1,this.allList=[],this.getInve(),setTimeout((function(){a()}),1e3)}},[["render",function(t,a,j,b,k,x){const v=g(s("uni-nav-bar"),c),y=o,L=g(s("up-loadmore"),f),I=g(s("up-empty"),h);return e(),i(y,null,{default:l((()=>[r(v,{title:"直属人数","status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:x.back},null,8,["onClickLeft"]),(e(!0),n(p,null,u(k.allList,((t,a)=>(e(),i(y,{class:"page_list_item",key:a},{default:l((()=>[r(y,{style:{height:"30rpx"}}),r(y,{class:"list_header"},{default:l((()=>[r(y,{class:"munds1"},{default:l((()=>[r(y,{class:"mr_5"},{default:l((()=>[d(_(t.name),1)])),_:2},1024)])),_:2},1024),r(y,{class:"munds2"})])),_:2},1024),r(y,{class:"list_middle"},{default:l((()=>[r(y,{class:"page_head"},{default:l((()=>[r(y,{class:"register_time"},{default:l((()=>[d(" 手机号 ")])),_:1}),r(y,{class:"mr_5"},{default:l((()=>[d(_(t.mobile),1)])),_:2},1024)])),_:2},1024),r(y,{class:"yui_la"},{default:l((()=>[r(y,{class:"register_time"},{default:l((()=>[d(" 注册时间 ")])),_:1}),r(y,{class:"mr_5"},{default:l((()=>[d(_(t.create_time_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128)),k.allList.length>0?(e(),i(L,{key:0,status:k.status,onClick:x.onReachBottom},null,8,["status","onClick"])):m("",!0),0==k.allList.length?(e(),i(I,{key:1,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png"})):m("",!0)])),_:1})}],["__scopeId","data-v-5f06bb04"]]);export{k as default};
