import{Z as t,aj as a,x as s,y as e,z as i,B as l,C as o,H as r,D as n,E as u,F as m,G as p,I as d,J as _}from"./index-eZLOZRr1.js";import{_ as c}from"./uni-nav-bar.D0OBKF7O.js";import{r as g}from"./uni-app.es.Cg_AhWrk.js";import{_ as h}from"./u-loadmore.iPdaFlF5.js";import{_ as f}from"./u-empty.B35TMJs6.js";import{r as j}from"./index.pCJYbHY4.js";import{_ as b}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DZmOUZ9v.js";import"./uni-status-bar.dIgvpYbO.js";import"./u-line.Dqz3ABQq.js";import"./u-loading-icon.C9PbYIxd.js";import"./u-icon.Cinv0AqG.js";const k=b({data:()=>({page:1,allList:[],per_page:1,status:"loadmore"}),methods:{back:t,getInve(){j({page:this.page}).then((t=>{for(let a=0;a<t.data.data.length;a++)this.allList.push(t.data.data[a]);this.status="loadmore",this.per_page=t.data.last_page}))},onReachBottom(){this.page>=this.per_page&&(this.status="nomore"),this.page>=this.per_page||(this.status="loading",this.page++,setTimeout((()=>{this.getInve()}),0))}},mounted(){this.getInve()},onPullDownRefresh(){this.page=1,this.allList=[],this.getInve(),setTimeout((function(){a()}),1e3)}},[["render",function(t,a,j,b,k,x){const v=g(s("uni-nav-bar"),c),y=l,L=g(s("up-loadmore"),h),I=g(s("up-empty"),f);return o(),e(y,null,{default:i((()=>[r(v,{title:"团队人数","status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:x.back},null,8,["onClickLeft"]),(o(!0),n(m,null,u(k.allList,((t,a)=>(o(),e(y,{class:"page_list_item",key:a},{default:i((()=>[r(y,{style:{height:"30rpx"}}),r(y,{class:"list_header"},{default:i((()=>[r(y,{class:"munds1"},{default:i((()=>[r(y,{class:"mr_5"},{default:i((()=>[d(_(t.name),1)])),_:2},1024)])),_:2},1024),r(y,{class:"munds2"})])),_:2},1024),r(y,{class:"list_middle"},{default:i((()=>[r(y,{class:"page_head"},{default:i((()=>[r(y,{class:"register_time"},{default:i((()=>[d(" 手机号 ")])),_:1}),r(y,{class:"mr_5"},{default:i((()=>[d(_(t.mobile),1)])),_:2},1024)])),_:2},1024),r(y,{class:"yui_la"},{default:i((()=>[r(y,{class:"register_time"},{default:i((()=>[d(" 注册时间 ")])),_:1}),r(y,{class:"mr_5"},{default:i((()=>[d(_(t.create_time_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128)),k.allList.length?(o(),e(L,{key:0,status:k.status,onClick:x.onReachBottom},null,8,["status","onClick"])):p("",!0),0===k.allList.length?(o(),e(I,{key:1,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png"})):p("",!0)])),_:1})}],["__scopeId","data-v-d41b21a3"]]);export{k as default};
