import{bX as t,q as e,bM as s,w as a,x as i,y as l,z as u,A as c,C as o,H as n,G as _,I as m,J as r,N as p}from"./index-DXZh4TrB.js";import{_ as d}from"./u-button.MNc3G8B8.js";import{r as g}from"./uni-app.es.BpJJwZA9.js";import{L as f,M as y}from"./index.qP49loVj.js";import{_ as x}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-loading-icon.D8mSLZ2D.js";import"./u-icon.CtR5oXjZ.js";const D=""+new URL("success_circle-Dq8_iC76.png",import.meta.url).href;const b=x({data:()=>({pageData:"",estimate:0}),methods:{copyText(s){t({data:s,success:function(){e({title:"复制成功",icon:"success",duration:2e3})},fail:function(){console.log("复制失败")}})},skip(){s({url:"/pages/copy-trading/copy-trading"})}},onLoad(t){1==t.estimate?(a({title:t.title}),this.estimate=t.estimate,f({id:t.id}).then((t=>{this.pageData=t.data}))):y({id:t.id}).then((t=>{this.pageData=t.data.form}))}},[["render",function(t,e,s,a,f,y){const x=p,b=o,$=g(i("up-button"),d);return l(),u(b,null,{default:c((()=>[n(b,{class:"page_top_bg"},{default:c((()=>[0==f.estimate?(l(),u(x,{key:0,src:D,mode:""})):_("",!0),1==f.estimate?(l(),u(x,{key:1,src:"",mode:""})):_("",!0),n(b,{class:"order_status"},{default:c((()=>[m(r(t.$t("buyingResult.text.applicationTime")),1)])),_:1}),n(b,{class:"order_time"},{default:c((()=>[m(r(f.pageData.create_time_text),1)])),_:1})])),_:1}),n(b,{class:"main_order_info"},{default:c((()=>[0==f.estimate?(l(),u(b,{key:0,class:"order_info_top"},{default:c((()=>[n(b,{class:"order_info_top_lse"},{default:c((()=>[m(r(f.pageData.name),1)])),_:1}),n(b,{class:"order_info_top_lse"},{default:c((()=>[m(r(f.pageData.money)+r(t.$t("common.text.currencyUnit")),1)])),_:1})])),_:1})):_("",!0),1==f.estimate?(l(),u(b,{key:1,class:"order_info_top"},{default:c((()=>[n(b,{class:"order_info_top_lse"},{default:c((()=>[m(r(f.pageData.stockname)+"("+r(f.pageData.stockcode)+") ",1)])),_:1}),n(b,{class:"order_info_top_lse"})])),_:1})):_("",!0),0==f.estimate?(l(),u(b,{key:2,class:"status_list"},{default:c((()=>[n(b,{class:"status_list_lase"},{default:c((()=>[n(b,{class:"mun1"}),n(b,{class:"mun2"})])),_:1}),n(b,{class:"status_list_rase"},{default:c((()=>[n(b,{class:"status_list_item"},{default:c((()=>[m(r(t.$t("buyingResult.text.expected"))+r(f.pageData.confirm_time_text)+r(t.$t("buyingResult.text.tip1")),1)])),_:1}),n(b,{class:"status_list_items"},{default:c((()=>[m(r(t.$t("buyingResult.text.expected"))+r(f.pageData.income_time_text)+r(t.$t("buyingResult.text.tip2")),1)])),_:1})])),_:1})])),_:1})):_("",!0),1==f.estimate?(l(),u(b,{key:3,class:"status_lists"},{default:c((()=>[n(b,{class:"status_list_lase"},{default:c((()=>[n(b,{class:"mun2"}),n(b,{class:"mun1"}),n(b,{class:"mun2"}),n(b,{class:"mun1"}),n(b,{class:"mun2"}),n(b,{class:"mun1"}),n(b,{class:"mun2"}),n(b,{class:"mun1"}),n(b,{class:"mun2"}),n(b,{class:"mun1"}),n(b,{class:"mun2"}),n(b,{class:"mun1"}),n(b,{class:"mun2"}),n(b,{class:"mun1"})])),_:1}),n(b,{class:"status_list_rase"},{default:c((()=>[n(b,{class:"status_list_item"},{default:c((()=>[m(r(f.pageData.buy_time_text)+" "+r(t.$t("buyingResult.text.syndicate"))+r(Number(f.pageData.buy_price*f.pageData.num).toFixed(2))+r(t.$t("common.text.currencyUnit")),1)])),_:1}),n(b,{class:"status_list_item"},{default:c((()=>[m(r(t.$t("buyingResult.text.purchaseCost"))+r(f.pageData.buy_cost?f.pageData.buy_cost:"--")+r(t.$t("common.text.currencyUnit")),1)])),_:1}),n(b,{class:"status_list_item"},{default:c((()=>[m(" 买入价格："+r(f.pageData.buy_price)+"元，买入数量："+r(f.pageData.num)+"股，下仓比例："+r(f.pageData.position)+"% ",1)])),_:1}),f.pageData.sell_time?(l(),u(b,{key:0,class:"status_list_item"},{default:c((()=>[m(r(f.pageData.sell_time_text)+" "+r(t.$t("buyingResult.text.tip4"))+r(Number(f.pageData.sell_price*f.pageData.num).toFixed(2)>0?Number(f.pageData.sell_price*f.pageData.num).toFixed(2):"--")+r(t.$t("common.text.currencyUnit")),1)])),_:1})):_("",!0),n(b,{class:"status_list_item"},{default:c((()=>[m(r(t.$t("buyingResult.text.sellingCost"))+r(f.pageData.sell_cost)+r(t.$t("common.text.currencyUnit"))+"，卖出价格: "+r(f.pageData.sell_price?f.pageData.sell_price:"--")+"元 ",1)])),_:1}),n(b,{class:"status_list_item"},{default:c((()=>[m(r(t.$t("buyingResult.text.lecturerCommission"))+r(f.pageData.commission)+r(t.$t("common.text.currencyUnit")),1)])),_:1}),f.pageData.system_commission?(l(),u(b,{key:1,class:"status_list_item"},{default:c((()=>[m(" 平台佣金"+r(f.pageData.system_commission)+r(t.$t("common.text.currencyUnit")),1)])),_:1})):_("",!0),n(b,{class:"status_list_item"},{default:c((()=>[m(r(t.$t("buyingResult.text.orderProfit"))+r(f.pageData.profit)+r(t.$t("common.text.currencyUnit")),1)])),_:1})])),_:1})])),_:1})):_("",!0),n(b,{class:"order_num"},{default:c((()=>[m(r(t.$t("buyingResult.text.orderNumber"))+r(f.pageData.order_sn)+" ",1),n($,{type:"primary",size:"mini",text:"复制",onClick:e[0]||(e[0]=t=>y.copyText(f.pageData.order_sn))})])),_:1})])),_:1}),0==f.estimate?(l(),u(b,{key:0,class:"continue_to_buy",onClick:e[1]||(e[1]=t=>y.skip())},{default:c((()=>[m(r(t.$t("buyingResult.text.orderProfit")),1)])),_:1})):_("",!0)])),_:1})}],["__scopeId","data-v-9f04c96c"]]);export{b as default};
