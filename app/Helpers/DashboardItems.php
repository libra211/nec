<?php

namespace App\Helpers;

use App\Models\DashboardVisibility;

/**
 * Central registry of togglable dashboard components per role.
 *
 * Each item key maps to a visible section on the role's dashboard.
 * Non-superadmin roles default to hidden unless enabled here.
 */
class DashboardItems
{
    /**
     * Role => [ key => label ]
     */
    public static function catalog(): array
    {
        $roleKey = [
            'state_coordinator' => [
                'state_kpis'      => 'Core stats (State Voters, Today, Week, Month)',
                'state_status'    => 'Status stats (Active, Inactive, Diaspora, Pending Transfers)',
                'state_trend'     => 'Registration Trend chart',
                'state_county'    => 'Voters by County chart',
                'state_age'       => 'Age Distribution chart',
                'state_reg_type'  => 'Registration Type chart',
                'state_transfers' => 'Pending Transfer Queue',
                'state_team'      => 'State Election Team',
                'state_recent'    => 'Recent Registrations in State',
            ],
            'constituency_officer' => [
                'constituency_kpis'    => 'Core stats (Stations, Today, Pending Transfers)',
                'constituency_break'   => 'Demographic stats (Total, Male, Female, Active Stations)',
                'constituency_recent'  => 'Recent Registrations',
            ],
            'registration_officer' => [
                'reg_kpis'       => 'Core stats (Today, Week, My Total, Voters)',
                'reg_charts'     => 'Trend + Gender Split charts',
                'reg_recent'     => 'Recent Registrations',
                'reg_actions'    => 'Register / Manage quick actions',
            ],
            'polling_officer' => [
                'po_kpis'           => 'Core stats (Stations, Active, Voters, Results)',
                'po_station_load'   => 'Station Load (Top)',
                'po_recent_results' => 'Recent Results',
            ],
            'data_entry' => [
                'de_kpis'    => 'Core stats (Today, Week, My Total, State Voters)',
                'de_actions' => 'Quick actions',
                'de_recent'  => 'Recent Registrations',
            ],
        ];

        return $roleKey;
    }

    /**
     * All catalogued roles (order matters for the settings UI).
     */
    public static function roles(): array
    {
        return array_keys(self::catalog());
    }

    /**
     * Labels for roles in the settings UI.
     */
    public static function roleLabel(string $role): string
    {
        $map = [
            'state_coordinator'    => 'State Coordinator',
            'constituency_officer' => 'Constituency Officer',
            'registration_officer' => 'Registration Officer',
            'polling_officer'      => 'Polling Officer',
            'data_entry'           => 'Data Entry',
        ];

        return $map[$role] ?? ucwords(str_replace('_', ' ', $role));
    }

    /**
     * Which keys are enabled for a role, as a set (map key => true).
     * When no rows exist yet, defaults to all-on for safety/back-compat.
     */
    public static function enabledKeys(string $role): array
    {
        $keys = self::catalog()[$role] ?? [];

        $rows = DashboardVisibility::where('role', $role)
            ->whereIn('key', array_keys($keys))
            ->pluck('visible', 'key');

        // Absent rows mean "not yet configured" -> default visible.
        $enabled = [];
        foreach ($keys as $key => $label) {
            $enabled[$key] = $rows->has($key) ? (bool) $rows[$key] : true;
        }

        return $enabled;
    }

    /**
     * True if a given component key is enabled for a role.
     */
    public static function enabled(string $role, string $key): bool
    {
        return (bool) (self::enabledKeys($role)[$key] ?? false);
    }
}