<?php

declare(strict_types=1);
/**
 * Playground
 */

namespace Playground\Test\Models;

use Database\Factories\Playground\Test\Models\WidgetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Playground\Models\Model;

/**
 * \Playground\Test\Models\Widget
 *
 * @property string $id
 * @property ?scalar $created_by_id
 * @property ?scalar $modified_by_id
 * @property ?scalar $owned_by_id
 * @property ?string $parent_id
 * @property ?string $widget_type
 * @property ?string $demo_id
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $deleted_at
 * @property int $gids
 * @property int $po
 * @property int $pg
 * @property int $pw
 * @property bool $only_admin
 * @property bool $only_user
 * @property bool $only_guest
 * @property bool $allow_public
 * @property int $status
 * @property int $rank
 * @property int $size
 * @property ?array<string, mixed> $matrix
 * @property ?int $x
 * @property ?int $y
 * @property ?int $z
 * @property ?float $r
 * @property ?float $theta
 * @property ?float $rho
 * @property ?float $phi
 * @property ?float $elevation
 * @property ?float $latitude
 * @property ?float $longitude
 * @property bool $active
 * @property bool $flagged
 * @property bool $internal
 * @property bool $locked
 * @property bool $unknown
 * @property string $label
 * @property string $title
 * @property string $byline
 * @property ?string $slug
 * @property string $url
 * @property string $description
 * @property string $introduction
 * @property ?string $content
 * @property ?string $summary
 * @property string $icon
 * @property string $image
 * @property string $avatar
 * @property ?array<string, mixed> $ui
 * @property ?array<string, mixed> $assets
 * @property ?array<string, mixed> $meta
 * @property ?array<int, array<string, mixed>> $notes
 * @property ?array<string, mixed> $options
 * @property ?array<string, mixed> $sources
 */
class Widget extends Model
{
    /** @use HasFactory<WidgetFactory> */
    use HasFactory;

    protected $table = 'testing_widgets';

    /**
     * The demo of the widget.
     *
     * @return HasOne<Demo, $this>
     */
    public function demo(): HasOne
    {
        return $this->hasOne(
            Demo::class,
            'id',
            'demo_id'
        );
    }
}
