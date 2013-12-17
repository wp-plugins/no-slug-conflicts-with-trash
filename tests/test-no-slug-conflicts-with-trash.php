<?php

class No_Slug_Conflict_With_Trash_Test extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		$this->meta_key = c2c_No_Slug_Conflicts_With_Trash::get_meta_key();
	}

	function remove_plugin_hooks() {
		// Remove default hooks
		$obj = c2c_No_Slug_Conflicts_With_Trash::get_instance();
		remove_filter( 'wp_unique_post_slug',    array( $obj, 'wp_unique_post_slug' ), 10, 6 );
		remove_action( 'transition_post_status', array( $obj, 'maybe_restore_changed_slug' ), 10, 3 );
	}

	function test_page_trash_then_immediately_untrash() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );

		$post_a = get_post( $post_id_a );

		$this->assertEquals( 'about', $post_a->post_name );
		$this->assertEmpty( get_post_meta( $post_id_a, $this->meta_key, true ) );
	}

	function test_page_trashA_createB() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );

		$this->assertEquals( 'about-2', $post_a->post_name );
		$this->assertEquals( 'about', get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about', $post_b->post_name );
	}

	function test_page_trashA_createB_then_untrashA() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_untrash_post( $post_id_a );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );

		$this->assertEquals( 'about-2', $post_a->post_name );
		$this->assertEmpty( get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about', $post_b->post_name );
		$this->assertEmpty( get_post_meta( $post_id_b, $this->meta_key, true ) );
	}

	function test_page_trashA_trashB_then_untrashA() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_b );
		wp_untrash_post( $post_id_a );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );

		$this->assertEquals( 'about', $post_a->post_name );
		$this->assertEmpty( get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about-2-2', $post_b->post_name );
		$this->assertEquals( 'about', get_post_meta( $post_id_b, $this->meta_key, true ) );
	}

	function test_page_trashA_trashB_then_untrashB() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_b );
		wp_untrash_post( $post_id_b );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );

		$this->assertEquals( 'about-2', $post_a->post_name );
		$this->assertEquals( 'about', get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about', $post_b->post_name );
		$this->assertEmpty( get_post_meta( $post_id_b, $this->meta_key, true ) );
	}

	function test_page_trashA_trashB_then_untrashA_untrashB() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_b );
		wp_untrash_post( $post_id_a );
		wp_untrash_post( $post_id_b );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );

		$this->assertEquals( 'about', $post_a->post_name );
		$this->assertEmpty( get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about-2-2', $post_b->post_name );
		$this->assertEmpty( get_post_meta( $post_id_b, $this->meta_key, true ) );
	}

	function test_page_trashA_trashB_then_createC() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_b );
		$post_id_c = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );
		$post_c = get_post( $post_id_c );

		$this->assertEquals( 'about-2', $post_a->post_name );
		$this->assertEquals( 'about', get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about-3', $post_b->post_name );
		$this->assertEquals( 'about', get_post_meta( $post_id_b, $this->meta_key, true ) );
		$this->assertEquals( 'about', $post_c->post_name );
		$this->assertEmpty( get_post_meta( $post_id_c, $this->meta_key, true ) );
	}

	function test_page_trashA_trashB_then_createC_with_number_suffixed_slug() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_b );
		$post_id_c = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about-2' ) );
		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );
		$post_c = get_post( $post_id_c );

		$this->assertEquals( 'about-2-2', $post_a->post_name );
		$this->assertEquals( 'about', get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about', $post_b->post_name );
		$this->assertEmpty( get_post_meta( $post_id_b, $this->meta_key, true ) );
		$this->assertEquals( 'about-2', $post_c->post_name );
		$this->assertEmpty( get_post_meta( $post_id_c, $this->meta_key, true ) );
	}

	function test_page_conflicts_in_same_hierarchy() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'alpha' ) );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'bravo', 'post_parent' => $post_id_a ) );
		wp_trash_post( $post_id_b );
		$post_id_c = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'bravo', 'post_parent' => $post_id_a ) );

		$post_b = get_post( $post_id_b );
		$post_c = get_post( $post_id_c );

		$this->assertEquals( 'bravo-2', $post_b->post_name );
		$this->assertEquals( 'bravo', get_post_meta( $post_id_b, $this->meta_key, true ) );
		$this->assertEquals( 'bravo', $post_c->post_name );
	}

	function test_page_no_conflicts_in_different_hierarchies() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'alpha' ) );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'bravo' ) );

		$post_id_c = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'charlie', 'post_parent' => $post_id_a ) );
		wp_trash_post( $post_id_c );
		$post_id_d = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'charlie', 'post_parent' => $post_id_b ) );

		$post_c = get_post( $post_id_c );
		$post_d = get_post( $post_id_d );

		$this->assertEquals( 'charlie', $post_c->post_name );
		$this->assertEmpty( get_post_meta( $post_id_c, $this->meta_key, true ) );
		$this->assertEquals( 'charlie', $post_d->post_name );
	}

	function test_attachment_conflict_with_page() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'attachment', 'post_name' => 'about' ) );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );

		$this->assertEquals( 'about-2', $post_a->post_name );
		$this->assertEquals( 'about', get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about', $post_b->post_name );
	}

	function test_attachment_conflict_with_post() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'post', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'attachment', 'post_name' => 'about' ) );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );

		$this->assertEquals( 'about-2', $post_a->post_name );
		$this->assertEquals( 'about', get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about', $post_b->post_name );
	}

	function test_page_does_not_conflict_with_post() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'post', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );

		$this->assertEquals( 'about', $post_a->post_name );
		$this->assertEmpty( get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about', $post_b->post_name );
	}

	function test_post_does_not_conflict_with_page() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'post', 'post_name' => 'about' ) );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );

		$this->assertEquals( 'about', $post_a->post_name );
		$this->assertEmpty( get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about', $post_b->post_name );
	}

	// Except for those that are hierarchical-related, most tests involving page apply for post too.

	function test_post_trashA_createB() {
		$post_id_a = $this->factory->post->create( array( 'post_type' => 'post', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'post', 'post_name' => 'about' ) );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );

		$this->assertEquals( 'about-2', $post_a->post_name );
		$this->assertEquals( 'about', get_post_meta( $post_id_a, $this->meta_key, true ) );
		$this->assertEquals( 'about', $post_b->post_name );
	}

	function test_version() {
		$this->assertEquals( '1.0.1', c2c_No_Slug_Conflicts_With_Trash::version() );
	}

	/**
	 * NOTE: TEST THIS LAST
	 */
	function test_default_wp_slug_conflict() {
		$this->remove_plugin_hooks();

		$post_id_a = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );
		wp_trash_post( $post_id_a );
		$post_id_b = $this->factory->post->create( array( 'post_type' => 'page', 'post_name' => 'about' ) );

		$post_a = get_post( $post_id_a );
		$post_b = get_post( $post_id_b );

		$this->assertEquals( 'about', $post_a->post_name );
		$this->assertEquals( 'about-2', $post_b->post_name );
	}

}

