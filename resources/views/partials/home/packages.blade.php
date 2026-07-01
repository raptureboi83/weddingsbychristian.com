<section id="packages" class="section section-alt">
    <div class="container">
        <div class="section-heading section-heading-spaced">
            <div class="eyebrow">{{ $packagesSection?->eyebrow ?: 'Services' }}</div>
            <h2 class="section-title">{{ $packagesSection?->title ?: 'Wedding Film Collections' }}</h2>

            @if ($packagesSection?->description)
                <div class="section-copy">{{ $packagesSection->description }}</div>
            @endif

            @if ($packagesSection?->intro)
                <div class="section-copy section-copy-spaced">{{ $packagesSection->intro }}</div>
            @endif
        </div>

        <div class="package-grid">
            @foreach ($packages as $package)
                <div class="package-card">
                    <h3>{{ $package->name }}</h3>

                    @if ($package->starting_price || $package->price_label)
                        <div class="package-price">
                            @if (!is_null($package->starting_price))
                                From ${{ number_format((float) $package->starting_price, 0) }}
                            @elseif ($package->price_label)
                                {{ $package->price_label }}
                            @endif
                        </div>
                    @endif

                    @if ($package->short_description)
                        <div class="package-copy package-copy-spaced">{{ $package->short_description }}</div>
                    @elseif ($package->description)
                        <div class="package-copy package-copy-spaced">{{ $package->description }}</div>
                    @endif

                    @if ($package->items->isNotEmpty())
                        <ul class="package-list">
                            @foreach ($package->items as $item)
                                <li>{{ $item->label }}</li>
                            @endforeach
                        </ul>
                    @elseif (is_array($package->deliverables) && count($package->deliverables))
                        <ul class="package-list">
                            @foreach ($package->deliverables as $deliverable)
                                <li>{{ $deliverable }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach

        </div>

        @if ($packageSharedBlocks->isNotEmpty())
            @foreach ($packageSharedBlocks as $block)
                @if ($block->block_type === 'content-short')
                    <div class="shared-block-short-wrap">
                        <div class="package-card shared-block-short-card">
                            @if ($block->title)
                                <h3>{{ $block->title }}</h3>
                            @endif

                            @if ($block->content)
                                <div class="shared-block-copy shared-block-copy-preline">{{ $block->content }}</div>
                            @endif

                            @if ($block->cta_label && $block->cta_url)
                                <div class="shared-block-cta">
                                    <a href="{{ $block->cta_url }}" class="button-primary">{{ $block->cta_label }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

        @if ($packageSharedBlocks->isNotEmpty())
            @foreach ($packageSharedBlocks as $block)
                @if ($block->block_type !== 'content-short')
                    <div class="shared-block-grid">
                        <div class="shared-block-card">
                            @if ($block->title)
                                <h3>{{ $block->title }}</h3>
                            @endif

                            @if ($block->content)
                                <div class="shared-block-copy shared-block-copy-preline">{{ $block->content }}</div>
                            @endif

                            @if ($block->cta_label && $block->cta_url)
                                <div class="shared-block-cta">
                                    <a href="{{ $block->cta_url }}" class="button-primary">{{ $block->cta_label }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

        @if ($packagesSection?->bottom_heading || $packagesSection?->bottom_description || ($packagesSection?->cta_label && $packagesSection?->cta_url))
            <div class="packages-bottom">
                @if ($packagesSection->bottom_heading)
                    <h2>{{ $packagesSection->bottom_heading }}</h2>
                @endif
                @if ($packagesSection->bottom_description)
                    <div class="section-copy">{{ $packagesSection->bottom_description }}</div>
                @endif
                @if ($packagesSection->cta_label && $packagesSection->cta_url)
                    <div class="packages-bottom-cta">
                        <a href="{{ $packagesSection->cta_url }}" class="button-primary">{{ $packagesSection->cta_label }}</a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>