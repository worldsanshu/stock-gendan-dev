import{Z as t,ai as a,x as s,y as e,z as i,A as l,C as o,H as r,D as n,E as u,F as d,G as m,I as p,J as _}from"./index-BpfZrajd.js";import{_ as c}from"./uni-nav-bar.BZQCfLjz.js";import{r as g}from"./uni-app.es.C3RDKkKF.js";import{_ as h}from"./u-loadmore.Bz-sBCou.js";import{_ as f}from"./u-empty.BAQ49pnu.js";import{r as j}from"./index.B50efdrW.js";import{_ as b}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CkblPvCD.js";import"./uni-status-bar.CQB75Tz1.js";import"./u-line.CQDya69Z.js";import"./u-loading-icon.DWuwejaM.js";import"./u-icon.B3VNGynq.js";const k=b({data:()=>({page:1,allList:[],per_page:1,status:"loadmore"}),methods:{back:t,getInve(){j({page:this.page}).then((t=>{for(let a=0;a<t.data.data.length;a++)this.allList.push(t.data.data[a]);this.status="loadmore",this.per_page=t.data.last_page}))},onReachBottom(){this.page>=this.per_page&&(this.status="nomore"),this.page>=this.per_page||(this.status="loading",this.page++,setTimeout((()=>{this.getInve()}),0))}},mounted(){this.getInve()},onPullDownRefresh(){this.page=1,this.allList=[],this.getInve(),setTimeout((function(){a()}),1e3)}},[["render",function(t,a,j,b,k,x){const v=g(s("uni-nav-bar"),c),y=o,L=g(s("up-loadmore"),h),I=g(s("up-empty"),f);return e(),i(y,null,{default:l((()=>[r(v,{title:"团队人数","status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:x.back},null,8,["onClickLeft"]),(e(!0),n(d,null,u(k.allList,((t,a)=>(e(),i(y,{class:"page_list_item",key:a},{default:l((()=>[r(y,{style:{height:"30rpx"}}),r(y,{class:"list_header"},{default:l((()=>[r(y,{class:"munds1"},{default:l((()=>[r(y,{class:"mr_5"},{default:l((()=>[p(_(t.name),1)])),_:2},1024)])),_:2},1024),r(y,{class:"munds2"})])),_:2},1024),r(y,{class:"list_middle"},{default:l((()=>[r(y,{class:"page_head"},{default:l((()=>[r(y,{class:"register_time"},{default:l((()=>[p(" 手机号 ")])),_:1}),r(y,{class:"mr_5"},{default:l((()=>[p(_(t.mobile),1)])),_:2},1024)])),_:2},1024),r(y,{class:"yui_la"},{default:l((()=>[r(y,{class:"register_time"},{default:l((()=>[p(" 注册时间 ")])),_:1}),r(y,{class:"mr_5"},{default:l((()=>[p(_(t.create_time_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128)),k.allList.length?(e(),i(L,{key:0,status:k.status,onClick:x.onReachBottom},null,8,["status","onClick"])):m("",!0),0===k.allList.length?(e(),i(I,{key:1,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png"})):m("",!0)])),_:1})}],["__scopeId","data-v-d41b21a3"]]);export{k as default};
