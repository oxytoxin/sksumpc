<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Pages\Cashier\CashierTransactionsPage;
use App\Filament\App\Resources\AccountResource;
use App\Filament\App\Resources\BalanceForwardedSummaryResource;
use App\Filament\App\Resources\CashCollectibleBillingResource;
use App\Filament\App\Resources\CashCollectibleResource;
use App\Filament\App\Resources\CapitalSubscriptionResource;
use App\Filament\App\Resources\CivilStatusResource;
use App\Filament\App\Resources\DisapprovalReasonResource;
use App\Filament\App\Resources\DisbursementVoucherResource;
use App\Filament\App\Resources\DivisionResource;
use App\Filament\App\Resources\JournalEntryVoucherResource;
use App\Filament\App\Resources\LoanApplicationResource;
use App\Filament\App\Resources\LoanResource;
use App\Filament\App\Resources\LoanTypeResource;
use App\Filament\App\Resources\MemberResource;
use App\Filament\App\Resources\MemberTypeResource;
use App\Filament\App\Resources\OccupationResource;
use App\Filament\App\Resources\OfficersListResource;
use App\Filament\App\Resources\OrganizationResource;
use App\Filament\App\Resources\PositionResource;
use App\Filament\App\Resources\ReligionResource;
use App\Filament\App\Resources\UserResource;
use Filament\Pages\Page;

class QuickLinks extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected string $view = 'filament.app.pages.quick-links';

    protected static ?int $navigationSort = -2;

    protected ?string $heading = 'Quick Links';

    protected static ?string $navigationLabel = 'Quick Links';

    public function getViewData(): array
    {
        return [
            'groups' => [
                [
                    'label' => 'Cashier',
                    'icon' => 'icon-membership',
                    'items' => [
                        ['label' => 'Membership', 'url' => MemberResource::getUrl('index'), 'icon' => 'heroicon-o-users'],
                        ['label' => 'Organizations', 'url' => OrganizationResource::getUrl('index'), 'icon' => 'heroicon-o-building-office'],
                        ['label' => 'Transactions', 'url' => CashierTransactionsPage::getUrl(), 'icon' => 'heroicon-o-arrow-path'],
                        ['label' => 'Revolving Fund', 'url' => RevolvingFundManagement::getUrl(), 'icon' => 'heroicon-o-currency-dollar'],
                        ['label' => 'Reports', 'url' => Reports::getUrl(), 'icon' => 'heroicon-o-document-chart-bar'],
                    ],
                ],
                [
                    'label' => 'Loan',
                    'icon' => 'icon-loan',
                    'items' => [
                        ['label' => 'Loan Applications', 'url' => LoanApplicationResource::getUrl('index'), 'icon' => 'heroicon-o-document-text'],
                        ['label' => 'Loans For Voucher', 'url' => LoanResource::getUrl('index'), 'icon' => 'heroicon-o-ticket'],
                        ['label' => 'Approved Loans', 'url' => ApprovedLoanApplications::getUrl(), 'icon' => 'heroicon-o-check-circle'],
                        ['label' => 'Disapproved Loans', 'url' => DisapprovedLoanApplications::getUrl(), 'icon' => 'heroicon-o-x-circle'],
                    ],
                ],
                [
                    'label' => 'Share Capital',
                    'icon' => 'icon-share-capital',
                    'items' => [
                        ['label' => 'Stakeholders', 'url' => CashCollectibleBillingResource::getUrl('index'), 'icon' => 'heroicon-o-user-group'],
                        ['label' => 'Capital Subscriptions', 'url' => CapitalSubscriptionResource::getUrl('index'), 'icon' => 'heroicon-o-chart-bar'],
                        ['label' => 'Officers Lists', 'url' => OfficersListResource::getUrl('index'), 'icon' => 'heroicon-o-clipboard-document-list'],
                        ['label' => 'Positions', 'url' => PositionResource::getUrl('index'), 'icon' => 'heroicon-o-briefcase'],
                        ['label' => 'MMIGS', 'url' => Mmigs::getUrl(), 'icon' => 'heroicon-o-qr-code'],
                    ],
                ],
                [
                    'label' => 'Bookkeeping',
                    'icon' => 'icon-registry',
                    'items' => [
                        ['label' => 'Accounts', 'url' => AccountResource::getUrl('index'), 'icon' => 'heroicon-o-book-open'],
                        ['label' => 'Balance Forwarded', 'url' => BalanceForwardedSummaryResource::getUrl('index'), 'icon' => 'heroicon-o-scale'],
                        ['label' => 'Disbursement Vouchers', 'url' => DisbursementVoucherResource::getUrl('index'), 'icon' => 'heroicon-o-arrow-right-circle'],
                        ['label' => 'Journal Entry Vouchers', 'url' => JournalEntryVoucherResource::getUrl('index'), 'icon' => 'heroicon-o-pencil-square'],
                        ['label' => 'Users', 'url' => UserResource::getUrl('index'), 'icon' => 'heroicon-o-user'],
                    ],
                ],
                [
                    'label' => 'Management',
                    'icon' => 'heroicon-o-cog-6-tooth',
                    'items' => [
                        ['label' => 'Cash Collectibles', 'url' => CashCollectibleResource::getUrl('index'), 'icon' => 'heroicon-o-banknotes'],
                        ['label' => 'Loan Schedule', 'url' => LoanTypeResource::getUrl('index'), 'icon' => 'heroicon-o-calendar'],
                        ['label' => 'Disapproval Reasons', 'url' => DisapprovalReasonResource::getUrl('index'), 'icon' => 'heroicon-o-no-symbol'],
                        ['label' => 'Divisions', 'url' => DivisionResource::getUrl('index'), 'icon' => 'heroicon-o-map'],
                        ['label' => 'Occupations', 'url' => OccupationResource::getUrl('index'), 'icon' => 'heroicon-o-wrench'],
                        ['label' => 'Civil Statuses', 'url' => CivilStatusResource::getUrl('index'), 'icon' => 'heroicon-o-heart'],
                        ['label' => 'Religions', 'url' => ReligionResource::getUrl('index'), 'icon' => 'heroicon-o-bookmark'],
                        ['label' => 'Member Types', 'url' => MemberTypeResource::getUrl('index'), 'icon' => 'heroicon-o-identification'],
                    ],
                ],
            ],
        ];
    }
}
