<template>
	<section>
		<div class="row">
			<div class="col-md-12">

				<template v-if="comments.length === 0">
					<div class="text-center form-group">
						<h5>No comment and reply found</h5>
					</div>
				</template>

				<!-- The time line -->
				<div class="timeline">
					<!-- timeline item -->
					<div v-for="(comment, key) in comments" :key="key" >
						<i class="fas fa-comment-dots bg-primary"></i>
						<div class="timeline-item">
							<span class="time"><i class="fas fa-clock"></i> {{ parseCreatedAt(comment.comment.created_at) }}</span>
							<h3 class="timeline-header"><a href="#">{{ comment.user.fullname }}</a> commented on article</h3>
							<div class="timeline-body">
								{{ comment.comment.comment }}
							</div>
							<div class="timeline-footer">
								<a class="btn btn-primary btn-sm"  @click="approved(comment.approvedURL)" v-if="comment.comment.approval_date == null">Approved comment</a>
								<a class="btn btn-danger btn-sm"  @click="archivedOrRestore(comment.archiveURL)" v-if="comment.deleted_at == null">Delete comment</a>
								<a class="btn btn-warning btn-sm"  @click="archivedOrRestore(comment.restoreURL)" v-if="comment.deleted_at != null">Restore comment</a>
							</div>
						</div>

						<div class="col-md-12" v-if="comment.replies">
							<!-- Reply -->
							<div class="timeline">
								<!-- reply item -->
								<div v-for="reply in comment.replies">
									<i class="fas fa-comments bg-secondary"></i>
									<div class="timeline-item">
										<span class="time"><i class="fas fa-clock"></i> {{ parseCreatedAt(reply.created_at) }}</span>
										<h3 class="timeline-header"><a href="#">{{ reply.user.fullname }}</a> reply on this comment</h3>
										<div class="timeline-body">
											{{ reply.content }}
										</div>
										<div class="timeline-footer">
											<a class="btn btn-primary btn-sm"  @click="approved(reply.approvalURL)" v-if="reply.approval_date == null">Approved reply</a>
											<a class="btn btn-danger btn-sm"  @click="archivedOrRestore(reply.archiveURL)" v-if="reply.deleted_at == null">Delete reply</a>
											<a class="btn btn-warning btn-sm"  @click="archivedOrRestore(reply.restoreURL)" v-if="reply.deleted_at != null">Restore reply</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- reply end -->
					</div>
				</div>
			</div>
		</div>
		<!-- END timeline item -->
		<!-- <div v-for="(comment, key) in comments" :key="key" class="row rounded shadow xs-12 mt-5 p-3">
			<div class="d-flex align-items-center w-75">
				<img class="img rounded-circle" :src="comment.user.full_image" alt="Profile photo">
				<h5 class="ml-3">{{ comment.user.fullname }}</h5>
			</div>
			<div class="d-flex align-items-right w-25">
				<div class="ml-auto">
					<button class="btn" title="Approve Comment" @click="approveComment(comment.approvedURL)" v-if="comment.comment.approval_date == null"><i class="fa fa-check"></i></button>
					<button class="btn" title="Archive" @click="showConfirm"><i class="fa fa-trash"></i></button>
				</div>
			</div>
			<p class="my-4 w-100 comment">{{ comment.comment.comment }}</p>
			<span class="text-muted font-weight-bold">{{ parseCreatedAt(comment.comment.created_at) }}</span>
		</div> -->
	</section>
</template>

<style scoped>
	.img {
		width: 40px;
		height: 40px;
	}

	.comment {
		line-height: 1;
	}
</style>

<script>
	import moment from 'moment';
	import MethodsMixin from 'Mixins/confirm/methods.js';
	import PropsMixin from 'Mixins/confirm/props.js';

	export default {
		mounted() {
			this.fetchComments();
		},
		data: () => ({
			comments: []
		}),
		props: ['fetchUrl'],
		mixins: [ MethodsMixin, PropsMixin ],
		methods: {
			parseCreatedAt(date) {
				return moment(date).format('MMMM DD, YYYY h:m:s');
			},
			archive(ai, ci) {
				axios.post(`admin/articles/${ai}/comments/${ci}/archive`)
					.then(response => {
						const filtered = this.comments.filter(comment => comment.id !== response.data.id);
						this.comments = [ ...filtered ];
					});
			},

			fetchComments() {
				axios.get(this.fetchUrl)
					.then(response => {
						this.comments = [ ...response.data.comments ];
					});
			},

			approved(url) {
				axios.post(url)
					.then(response => {
						this.fetchComments();
					})
			},

			archivedOrRestore(url) {
				axios.post(url)
					.then(response => {
						this.fetchComments();
					})
			},
		}
	}
</script>
