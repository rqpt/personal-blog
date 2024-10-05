@props(['posts'])

@if ($posts->count() > 0)
  <section>
    <h2>Planned / WIP</h2>
    @foreach ($posts as $post)
      <article
        x-data="{
            focused: false,
        }"
        @mouseenter="focused = true"
        @mouseleave="focused = false"
      >
        <hgroup>
          <h3>
            {{ $post->title }}
          </h3>

          <p
            class="mobile-hidden"
            x-show="focused"
            x-collapse
            x-cloak
          >
            <small>
              {{ $post->frontmatter->description }}
            </small>
          </p>
        </hgroup>

        <footer>
          <small>
            <em>
              {{ $post->timestamps() }}
            </em>
          </small>
        </footer>
      </article>
    @endforeach
  </section>
@endif
