import Vue from 'vue';

Vue.component('dashboard-analytics', require('./views/dashboards/DashboardAnalytics.vue').default);

Vue.component('admin-users-table', require('./views/admin/admin-users/AdminsTable.vue').default);
Vue.component('admin-users-view', require('./views/admin/admin-users/AdminsView.vue').default);

Vue.component('users-table', require('./views/admin/users/UsersTable.vue').default);
Vue.component('users-view', require('./views/admin/users/UsersView.vue').default);
Vue.component('user-vouchers-table', require('./views/admin/users/UserVouchersTable.vue').default);

Vue.component('roles-table', require('./views/admin/roles/RolesTable.vue').default);
Vue.component('roles-view', require('./views/admin/roles/RoleView.vue').default);

Vue.component('pages-table', require('./views/admin/pages/PagesTable.vue').default);
Vue.component('pages-view', require('./views/admin/pages/PagesView.vue').default);

Vue.component('page-items-table', require('./views/admin/pages/PageItemsTable.vue').default);
Vue.component('page-items-view', require('./views/admin/pages/PageItemsView.vue').default);

Vue.component('permissions-list', require('./views/admin/permissions/PermissionList.vue').default);


/*
|--------------------------------------------------------------------------
| @Reports VUE components
|--------------------------------------------------------------------------
*/
Vue.component('reports', require('./views/admin/reports/Reports.vue').default);


/*
|--------------------------------------------------------------------------
| @MedRep Target Vue component
|--------------------------------------------------------------------------
*/
Vue.component('medrep-targets-view', require('./views/admin/medrep-targets/MedRepTargetsView.vue').default);

/*
|--------------------------------------------------------------------------
| @MedReps Vue component
|--------------------------------------------------------------------------
*/
Vue.component('medreps-location-logs', require('./views/admin/medreps/MedRepLocationLogs.vue').default);
Vue.component('medreps-table', require('./views/admin/medreps/MedRepsTable.vue').default);
Vue.component('medreps-view', require('./views/admin/medreps/MedRepsView.vue').default);
Vue.component('medreps-report', require('./views/admin/medreps/MedRepsReportTable.vue').default);


Vue.component('cities-table', require('./views/admin/cities/CitiesTable.vue').default);
Vue.component('cities-view', require('./views/admin/cities/CitiesView.vue').default);

Vue.component('target-calls-table', require('./views/admin/target-calls/TargetCallsTable.vue').default);
Vue.component('target-calls-view', require('./views/admin/target-calls/TargetCallsView.vue').default);

Vue.component('redeems-table', require('./views/admin/redeems/RedeemsTable.vue').default);
/**
 * Admin Components
 */

/*
|--------------------------------------------------------------------------
| @Invoices VUE components
|--------------------------------------------------------------------------
*/
Vue.component('invoices-table', require('./views/admin/invoices/InvoicesTable.vue').default);
Vue.component('invoice-items-table', require('./views/admin/invoices/InvoiceItemsTable.vue').default);
Vue.component('invoice-status-update', require('./views/admin/invoices/InvoiceStatusUpdate.vue').default);
Vue.component('invoice-failed-transaction-form', require('./views/admin/invoices/failed/InvoiceFailedTransactionForm.vue').default);

/*
|--------------------------------------------------------------------------
| @Status Types VUE components
|--------------------------------------------------------------------------
*/
Vue.component('status-types-table', require('./views/admin/statustypes/StatusTypesTable.vue').default);
Vue.component('status-types-view', require('./views/admin/statustypes/StatusTypesView.vue').default);

/*
|--------------------------------------------------------------------------
| @Provinces VUE components
|--------------------------------------------------------------------------
*/
Vue.component('provinces-view', require('./views/admin/provinces/ProvincesView.vue').default);
Vue.component('provinces-table', require('./views/admin/provinces/ProvincesTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Products VUE components
|--------------------------------------------------------------------------
*/
Vue.component('products-table', require('./views/admin/products/ProductsTable.vue').default);
Vue.component('products-view', require('./views/admin/products/ProductView.vue').default);
Vue.component('products-import', require('./views/admin/products/ProductsImport.vue').default);

/*
|--------------------------------------------------------------------------
| @Product Parents
|--------------------------------------------------------------------------
*/
Vue.component('product-parent-view', require('./views/admin/product-parents/ProductParentView.vue').default);
Vue.component('product-parents-table', require('./views/admin/product-parents/ProductParentsTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Inventories VUE components
|--------------------------------------------------------------------------
*/
Vue.component('inventories-table', require('./views/admin/inventories/InventoriesTable.vue').default);
Vue.component('inventories-view', require('./views/admin/inventories/InventoriesView.vue').default);

/*
|--------------------------------------------------------------------------
| @Specializations VUE components
|--------------------------------------------------------------------------
*/
Vue.component('specializations-table', require('./views/admin/specializations/SpecializationsTable.vue').default);
Vue.component('specializations-view', require('./views/admin/specializations/SpecializationsView.vue').default);


/*
|--------------------------------------------------------------------------
| @Article Category VUE components
|--------------------------------------------------------------------------
*/
Vue.component('article-categories-view', require('./views/admin/article-categories/ArticleCategoriesView.vue').default);
Vue.component('article-categories-table', require('./views/admin/article-categories/ArticleCategoriesTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Articles VUE components
|--------------------------------------------------------------------------
*/
Vue.component('articles-table', require('./views/admin/articles/ArticlesTable.vue').default);
Vue.component('articles-view', require('./views/admin/articles/ArticlesView.vue').default);
Vue.component('comments-view', require('./views/admin/articles/CommentsView.vue').default);

/*
|--------------------------------------------------------------------------
| @Standards VUE components
|--------------------------------------------------------------------------
*/
Vue.component('shipping-standards-table', require('./views/admin/standards/StandardsTable.vue').default);
Vue.component('shipping-standards-view', require('./views/admin/standards/StandardsView.vue').default);

/*
|--------------------------------------------------------------------------
| @Expresses VUE components
|--------------------------------------------------------------------------
*/
Vue.component('shipping-expresses-table', require('./views/admin/expresses/ExpressesTable.vue').default);
Vue.component('shipping-expresses-view', require('./views/admin/expresses/ExpressesView.vue').default);

/*
|--------------------------------------------------------------------------
| @Bank Details VUE components
|--------------------------------------------------------------------------
*/
Vue.component('bank-details-table', require('./views/admin/bank-details/BankDetailsTable.vue').default);
Vue.component('bank-details-view', require('./views/admin/bank-details/BankDetailsView.vue').default);

/*
|--------------------------------------------------------------------------
| @Doctors VUE components
|--------------------------------------------------------------------------
*/
Vue.component('doctors-view', require('./views/admin/doctors/DoctorsView.vue').default);
Vue.component('doctors-table', require('./views/admin/doctors/DoctorsTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Calls VUE components
|--------------------------------------------------------------------------
*/
Vue.component('calls-view', require('./views/admin/calls/CallsView.vue').default);
Vue.component('calls-table', require('./views/admin/calls/CallsTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Points VUE components
|--------------------------------------------------------------------------
*/
Vue.component('points-table', require('./views/admin/points/PointsTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Rewards VUE components
|--------------------------------------------------------------------------
*/
Vue.component('rewards-table', require('./views/admin/rewards/RewardsTable.vue').default);
Vue.component('rewards-view', require('./views/admin/rewards/RewardView.vue').default);

/*
|--------------------------------------------------------------------------
| @Prescriptions VUE components
|--------------------------------------------------------------------------
*/
Vue.component('prescriptions-table', require('./views/admin/prescriptions/PrescriptionsTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Sponsorships VUE components
|--------------------------------------------------------------------------
*/
Vue.component('sponsorships-view', require('./views/admin/sponsorships/SponsorshipsView.vue').default);
Vue.component('sponsorships-table', require('./views/admin/sponsorships/SponsorshipsTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Faqs VUE components
|--------------------------------------------------------------------------
*/
Vue.component('faqs-view', require('./views/admin/faqs/FaqsView.vue').default);
Vue.component('faqs-table', require('./views/admin/faqs/FaqsTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Faq Categories VUE components
|--------------------------------------------------------------------------
*/
Vue.component('faq-categories-view', require('./views/admin/faq-categories/FaqCategoriesView.vue').default);
Vue.component('faq-categories-table', require('./views/admin/faq-categories/FaqCategoriesTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Announcemet Types VUE components
|--------------------------------------------------------------------------
*/
Vue.component('announcement-types-view', require('./views/admin/announcement-types/AnnouncementTypesView.vue').default);
Vue.component('announcement-types-table', require('./views/admin/announcement-types/AnnouncementTypesTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Announcemets VUE components
|--------------------------------------------------------------------------
*/
Vue.component('announcements-view', require('./views/admin/announcements/AnnouncementsView.vue').default);
Vue.component('announcements-table', require('./views/admin/announcements/AnnouncementsTable.vue').default);


/*
|--------------------------------------------------------------------------
| @Areas VUE components
|--------------------------------------------------------------------------
*/
Vue.component('area-view', require('./views/admin/areas/AreaView.vue').default);
Vue.component('areas-table', require('./views/admin/areas/AreasTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Shipping Matrix VUE components
|--------------------------------------------------------------------------
*/
Vue.component('shipping-matrix-view', require('./views/admin/shipping-matrixes/ShippingMatrixView.vue').default);
Vue.component('shipping-matrixes-table', require('./views/admin/shipping-matrixes/ShippingMatrixesTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Credit Packages VUE components
|--------------------------------------------------------------------------
*/
Vue.component('credit-package-view', require('./views/admin/credit-packages/CreditPackageView.vue').default);
Vue.component('credit-packages-table', require('./views/admin/credit-packages/CreditPackagesTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Payout VUE components
|--------------------------------------------------------------------------
*/
Vue.component('payouts-table', require('./views/admin/payouts/PayoutsTable.vue').default);
Vue.component('payout-disapproval-form', require('./views/admin/payouts/PayoutDisapprovalForm.vue').default);

/*
|--------------------------------------------------------------------------
| @Credit Invoices VUE components
|--------------------------------------------------------------------------
*/
Vue.component('credit-invoice-table', require('./views/admin/credit-invoices/CreditInvoiceTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Refund VUE components
|--------------------------------------------------------------------------
*/
Vue.component('refunds-table', require('./views/admin/refunds/RefundsTable.vue').default);
Vue.component('refund-view', require('./views/admin/refunds/RefundView.vue').default);
Vue.component('refund-disapproval-form', require('./views/admin/refunds/RefundDisapprovalForm.vue').default);


/*
|--------------------------------------------------------------------------
| @Pharmacies VUE components
|--------------------------------------------------------------------------
*/
Vue.component('pharmacies-view', require('./views/admin/pharmacies/PharmaciesView.vue').default);
Vue.component('pharmacies-table', require('./views/admin/pharmacies/PharmaciesTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Doctor Reviewers
|--------------------------------------------------------------------------
*/
Vue.component('doctor-reviews-table', require('./views/admin/doctor-reviews/DoctorReviewsTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Vouchers VUE components
|--------------------------------------------------------------------------
*/
Vue.component('vouchers-table', require('./views/admin/vouchers/VouchersTable.vue').default);
Vue.component('vouchers-view', require('./views/admin/vouchers/VouchersView.vue').default);

/*
|--------------------------------------------------------------------------
| @Used Vouchers Components
|--------------------------------------------------------------------------
*/
Vue.component('used-vouchers-table', require('./views/admin/used-vouchers/UsedVouchersTable.vue').default);


/*
|--------------------------------------------------------------------------
| @Request Claim Referral Components
|--------------------------------------------------------------------------
*/
Vue.component('request-claim-referrals-table', require('./views/admin/request-claim-referrals/RequestClaimReferralsTable.vue').default);
Vue.component('update-status', require('./views/admin/request-claim-referrals/UpdateStatus.vue').default);

/*
|--------------------------------------------------------------------------
| @Consultations Components
|--------------------------------------------------------------------------
*/
Vue.component('consultations-table', require('./views/admin/consultations/ConsultationsTable.vue').default);

/*
|--------------------------------------------------------------------------
| @Manage credits
|--------------------------------------------------------------------------
*/
Vue.component('manage-credits-form', require('./views/admin/manage-credits/ManageCreditsForm.vue').default);