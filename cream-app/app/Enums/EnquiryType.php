<?php

namespace App\Enums;

enum EnquiryType: string
{
    case Contact = 'contact';
    case Sell = 'sell';
    case LandSearch = 'land_search';
    case Listing = 'listing';
    case OffMarket = 'off_market';
    case NewProject = 'new_project';
    case ShortStay = 'short_stay';

    public function label(): string
    {
        return match ($this) {
            self::Contact => 'General enquiry',
            self::Sell => 'Sell / let a property',
            self::LandSearch => 'Land document search',
            self::Listing => 'Property enquiry',
            self::OffMarket => 'Off-market brief',
            self::NewProject => 'New-project interest',
            self::ShortStay => 'Short-stay request',
        };
    }

    /** Default subject line for the office notification email. */
    public function subject(): string
    {
        return match ($this) {
            self::Contact => 'New enquiry',
            self::Sell => 'Sell / let submission',
            self::LandSearch => 'Land document search request',
            self::Listing => 'Property enquiry',
            self::OffMarket => 'Off-market brief',
            self::NewProject => 'New-project — register interest',
            self::ShortStay => 'Short-stay — request dates',
        };
    }

    /**
     * Ordered list of detail keys this type may carry, with human labels,
     * used to render the staff detail view in a stable, readable order.
     *
     * @return array<string, string>
     */
    public function detailLabels(): array
    {
        return match ($this) {
            self::Contact => [
                'enquiry_type' => 'Reason', 'budget' => 'Budget (USD)',
                'timeline' => 'Timeline', 'newsletter' => 'Newsletter opt-in',
            ],
            self::Sell => [
                'property_location' => 'Property location', 'property_type' => 'Property type',
                'bedrooms' => 'Bedrooms', 'asking_price' => 'Asking price',
                'intent' => 'Sell or let', 'photos_link' => 'Photos link',
            ],
            self::LandSearch => [
                'plot_location' => 'Plot location', 'search_type' => 'Search type',
            ],
            self::OffMarket => [
                'location' => 'Location / area', 'budget' => 'Budget (USD)', 'bedrooms' => 'Bedrooms',
            ],
            self::NewProject => [
                'project' => 'Project',
            ],
            self::ShortStay => [
                'checkin' => 'Check-in', 'checkout' => 'Check-out', 'guests' => 'Guests',
            ],
            self::Listing => [],
        };
    }
}
