{{ \Carbon\Carbon::now(Auth::user()->timezone)->format(setting('time_format')) ?? 'logout' }}
