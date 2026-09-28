<?php

namespace App\Http\Middleware;

use App\Models\IpAddress;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class IpMiddleware
{
    /**
     * List of valid IPs.
     *
     * @var array
     */
    protected array $ips = [];

    /**
     * List of valid IP-ranges.
     *
     * @var array
     */
    protected array $ipRanges = [];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $ipsArr = IpAddress::all(['ip_address'])->toArray();

        foreach ($ipsArr as $ip) {
            $this->ips[] = $ip['ip_address'];
        }

        if ($request->path() !== '/' &&
            $request->path() !== 'getPointsOMJson' &&
            $request->path() !== 'getAll'
        ) {
            foreach ($request->getClientIps() as $ip) {
                if (! $this->isValidIp($ip) && ! $this->isValidIpRange($ip)) {
                    return redirect('/');
                }
            }
        }

        return $next($request);
    }

    /**
     * Check if the given IP is valid.
     *
     * @param $ip
     * @return bool
     */
    protected function isValidIp($ip): bool
    {
        return IpUtils::checkIp($ip, $this->ips);
    }

    /**
     * Check if the ip is in the given IP-range.
     *
     * @param $ip
     * @return bool
     */
    protected function isValidIpRange($ip): bool
    {
        return IpUtils::checkIp($ip, $this->ipRanges);
    }
}
